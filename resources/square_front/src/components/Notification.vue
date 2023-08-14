<template>
    <div class="px-5 py-[17px] rounded-[15px] bg-[#0062FF0D] w-[986px] flex items-center justify-between">
        <div class="flex gap-x-5">
            <div class="w-[42px] h-[42px] rounded-full overflow-hidden">
                <img class="w-full h-full" :src="getLastUserImage(notification.user)" alt="User image">
            </div>
            <div v-if="notification.notificationable_type.includes('Post')">
                <p class="text-[#171725] text-[15px]"><span class="font-semibold">{{notification.user.name}}</span> posted in <span class="font-semibold">UI/UX Community :</span> “{{notification.notificationable.description}}”</p>
                <span class="text-[#92929D] text-sm">{{notification.created_at}}</span>
            </div>
            <div v-else-if="notification.notificationable_type.includes('Like')">
                <p class="text-[#171725] text-[15px]"><span class="font-semibold">{{notification.user.name}}</span> liked your post <span class="font-semibold">UI/UX Community :</span> “{{notification.notificationable.likeable.description}}”</p>
                <span class="text-[#92929D] text-sm">{{notification.created_at}}</span>
            </div>
            <div v-else-if="notification.notificationable_type.includes('Comment')">
                <p class="text-[#171725] text-[15px]"><span class="font-semibold">{{notification.user.name}}</span> commented in <span class="font-semibold">{{notification.notificationable.commentable.description}}:</span> “{{notification.notificationable.comment}}”</p>
                <span class="text-[#92929D] text-sm">{{notification.created_at}}</span>
            </div>
            <div v-else-if="notification.notificationable_type.includes('User')">
                <p class="text-[#171725] text-[15px]"><span class="font-semibold">{{notification.user.name}}</span> has started to follow <span> You</span>
                </p>
                <span class="text-[#92929D] text-sm">{{notification.created_at}}</span>
            </div>
        </div>
        <div class="flex items-center gap-x-5">
            <div v-if="notification.notificationable_type.includes('Post')" class="w-[34px] h-[34px] bg-[#0062FF1A] flex items-center justify-center rounded-full">
                <FriendsIcon color="#50B5FF" size="22"></FriendsIcon>
            </div>
            <div v-else-if="notification.notificationable_type.includes('Like')" class="w-[34px] h-[34px] bg-[#FFC5421A] flex items-center justify-center rounded-full">
                <HeartIcon size="22" color="#FFC542"></HeartIcon>
            </div>
            <div v-else-if="notification.notificationable_type.includes('Comment')" class="w-[34px] h-[34px] bg-[#82C43C1A] flex items-center justify-center rounded-full">
                <CommentIcon size="22" color="#82C43C"></CommentIcon>
            </div>
            <div v-else-if="notification.notificationable_type.includes('User')" class="w-[34px] h-[34px] bg-[#82C43C1A] flex items-center justify-center rounded-full">
                <UserIcon></UserIcon>
            </div>
            <MoreOptions my-class="bg-transparent" dropdown-class="top-[-12px] right-[128px] py-1.5 px-2.5 gap-y-0.5">
                <button @click="$emit('remove_id', props.notification.user.id)" class="text-[12px] text-[#44444F] leading-[22px]">Remove Notifications of {{notification.user.name}}</button>
                <button @click="$emit('stop_id', props.notification.user.id)" class="text-[12px] text-[#44444F] leading-[22px]">Turn Off Notifications from {{notification.user.name}}</button>
            </MoreOptions>
        </div>
    </div>
</template>

<script setup>

import FriendsIcon from "./icons/FriendsIcon.vue";
import HeartIcon from "./icons/HeartIcon.vue";
import CommentIcon from "./icons/CommentIcon.vue";
import MoreOptions from "./MoreOptions.vue";
import {getLastUserImage} from "../utils/getImages.js";
import UserIcon from "./icons/UserIcon.vue";

const props = defineProps({
    notification: Object
})

</script>
