<template>
    <HomeLayout :userInfo="user" :notifications="notifications">
        <div class="mt-[33px] gap-y-[33px] flex flex-col items-center">
            <div class="flex items-center gap-x-[95px]">
                <h1 class="text-[#171725] text-[24px] font-semibold">Friends List</h1>
                <div class="bg-white text-[14px] p-3 rounded-[10px] text-[#696974]">
                    <span>Sort by:</span>
                    <select class="bg-transparent text-[#44444F] font-medium w-[100px]">
                        <option class="flex items-center justify-between" value="Hello">Hello</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-2 gap-8 pb-[95px]">
                <FriendCard v-for="friend in realFriends" :userImage="getLastUserImage(friend)" :background-image="getLastBackgroundImage(friend)" :user="friend"></FriendCard>
            </div>
        </div>
    </HomeLayout>
</template>

<script setup>

import HomeLayout from "../../components/HomeLayout.vue";
import FriendCard from "../../components/FriendCard.vue";
import {onMounted, onUnmounted, provide, ref} from 'vue';
import echo from "../../utils/echo.js";
import {router} from "@inertiajs/vue3";
import removeDuplicatedItems from "../../utils/removeDuplicatedItems.js";
import {getLastUserImage, getLastBackgroundImage} from "../../utils/getImages.js";

const props = defineProps({
    user: Object,
})

console.log(props.user);

const friends = ref([
    ...props.user.friend_of,
    ...props.user.friends_of_mine
])

const shouldNotify = ref(
    props.user.stop_notifications_from > 0 ?
        removeDuplicatedItems([
            ...props.user.following,
            ...friends.value
        ]).filter((user) => {
            for(const stop of props.user.stop_notifications_from) {
                if(user.id !== stop.id) {
                    return user;
                }
            }
        })
        :
        removeDuplicatedItems([
            ...props.user.following,
            ...friends.value
        ])
)

const realFriends = ref(friends.value.map((friend) => {
    for(const follow of props.user.following) {
        if(friend.id === follow.id) {
            friend.follow = true;
            return friend;
        }
    }
    return friend;
}));

console.log('realFriends',realFriends.value);

const notifications = ref(JSON.parse(localStorage.getItem('notifications')) ?? false);

console.log(friends.value);

let postListener, likesListener, notificationsListener;

onMounted(() => {
    shouldNotify.value.forEach((friend) => {
        notificationsListener = echo.channel(`notifications-${friend.id}`)
            .listen('NotificationCreated', (notification) => {

                localStorage.setItem('notifications', 'true');
                notifications.value = true;
            })
    })
})



onUnmounted(() => {
    notificationsListener?.stopListening('NotificationCreated');
})

</script>
