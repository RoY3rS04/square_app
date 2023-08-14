<template>
    <HomeLayout :userInfo="user" :notifications="notifications">
        <div class="mt-[33px] gap-y-[33px] flex flex-col items-center">
            <div class="flex items-center gap-x-[95px]">
                <h1 class="text-[#171725] text-[24px] font-semibold">Meet New People</h1>
                <div class="bg-white text-[14px] p-3 rounded-[10px] text-[#696974]">
                    <span>Sort by:</span>
                    <select class="bg-transparent text-[#44444F] font-medium w-[100px]">
                        <option class="flex items-center justify-between" value="Hello">Hello</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-8 pb-[95px]">
                <div class="rounded-[15px] bg-white px-[15px] py-[10px] flex flex-col gap-y-2" v-for="user in realUsers">
                    <UserCard :user="user"></UserCard>
                </div>
            </div>
        </div>
    </HomeLayout>
</template>

<script setup>

import HomeLayout from "../../components/HomeLayout.vue";
import FriendCard from "../../components/FriendCard.vue";
import {router} from "@inertiajs/vue3";
import {onMounted, onUnmounted, provide, ref} from "vue";
import echo from "../../utils/echo.js";
import UserCard from "../../components/UserCard.vue";
import removeDuplicatedItems from "../../utils/removeDuplicatedItems.js";

const props = defineProps({
    user: Object,
    users: Array,
})

const realUsers = ref(props.users.map((user) => {
    for(const follow of props.user.following) {
        if(user.id === follow.id) {
            user.follow = true;
            return user;
        }
    }
    return user;
}));

let postListener, likesListener, notificationsListener;

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

const notifications = ref(JSON.parse(localStorage.getItem('notifications')) ?? false);

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
