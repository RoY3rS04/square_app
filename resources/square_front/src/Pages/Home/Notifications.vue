<template>
    <HomeLayout :userInfo="user">
        <div class="flex flex-col gap-y-8 mt-8">
            <div class="flex items-center gap-x-7">
                <h1 class="text-[24px] text-[#171725] font-semibold">Notifications</h1>
                <div class="text-[18px] p-3 rounded-[10px] text-[#696974]">
                    <span>Show:</span>
                    <select @change="filterNotifications" class="bg-transparent text-[#44444F] font-medium w-[100px]">
                        <option class="flex items-center justify-between" value="all">All</option>
                        <option class="flex items-center justify-between" value="posts">Posts</option>
                        <option class="flex items-center justify-between" value="likes">Likes</option>
                        <option class="flex items-center justify-between" value="comments">Comments</option>
                        <option class="flex items-center justify-between" value="followers">Followers</option>
                    </select>
                </div>
            </div>
            <div class="flex flex-col pb-[95px] gap-y-[10px]">
                <Notification @stop_id="(userId) => {
                    stopNotificationsFrom(userId);
                }" @remove_id="(userId) => {
                    removeNotificationsFrom(userId);
                }" v-for="notification in searchedNotifications" :notification="notification"></Notification>
            </div>
        </div>
    </HomeLayout>
</template>

<script setup>

import HomeLayout from "../../components/HomeLayout.vue";
import Notification from "../../components/Notification.vue";
import {onMounted, onUnmounted, ref} from 'vue';
import echo from "../../utils/echo.js";
import {router} from "@inertiajs/vue3";
import {FILTER_POSTS, FILTER_ALL, FILTER_LIKES, FILTER_COMMENTS, FILTER_FOLLOWERS} from "../../consts/filter_consts.js";
import removeDuplicatedItems from "../../utils/removeDuplicatedItems.js";

const props = defineProps({
    user: Object,
    notifications: Array,
})

const realNotifications = ref(props.notifications);
const searchedNotifications = ref(realNotifications.value);


const friends = ref([
    ...props.user.friend_of,
    ...props.user.friends_of_mine
])

const shouldNotify = ref(
    removeDuplicatedItems([
        ...props.user.following,
        ...friends.value
    ]).filter((user) => {
        if(props.user.stop_notifications_from.length > 0) {
            for(const stop of props.user.stop_notifications_from) {
                if(user.id !== stop.id) {
                    return user;
                }
            }
        } else {
            return user;
        }
    })
)

function filterNotifications(e) {
    switch(e.target.value) {
        case FILTER_ALL: {
            searchedNotifications.value = realNotifications.value;
            break;
        }
        case FILTER_POSTS: {
            searchedNotifications.value = realNotifications.value.filter((notification) => {
                if (notification.notificationable_type.includes('Post')) {
                    return notification;
                }
            })

            console.log(searchedNotifications.value);
            break;
        }
        case FILTER_LIKES: {
            searchedNotifications.value = realNotifications.value.filter((notification) => {
                if (notification.notificationable_type.includes('Like')) {
                    return notification;
                }
            })
            break;
        }
        case FILTER_COMMENTS: {
            searchedNotifications.value = realNotifications.value.filter((notification) => {
                if (notification.notificationable_type.includes('Comment')) {
                    return notification;
                }
            })
            break;
        }
        case FILTER_FOLLOWERS: {
            searchedNotifications.value = realNotifications.value.filter((notification) => {
                if (notification.notificationable_type.includes('User')) {
                    return notification;
                }
            })
            break;
        }
    }
}

function stopNotificationsFrom(id) {
    router.post(`http://127.0.0.1:8000/notifications/stop/${id}`);
}

function removeNotificationsFrom(id) {
    router.delete(`http://127.0.0.1:8000/notifications/${id}`);

    searchedNotifications.value = searchedNotifications.value.filter((notification) => {
        if (notification.user.id !== id) {
            return notification
        }
    })

    console.log(searchedNotifications.value);
}

let postListener, likesListener, notificationsListener;

onMounted(() => {
    shouldNotify.value.forEach((friend) => {
        notificationsListener = echo.channel(`notifications-${friend.id}`)
            .listen('NotificationCreated', (notification) => {

                if(notification.notification.notificationable) {
                    realNotifications.value = [
                        ...realNotifications.value,
                        notification.notification
                    ]
                    searchedNotifications.value = [
                        ...searchedNotifications.value,
                        notification.notification
                    ]
                }
            })
    })
})

onUnmounted(() => {
    notificationsListener?.stopListening('NotificationCreated');
})
</script>
