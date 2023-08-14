<template>
    <div class="flex gap-x-4 items-center">
        <div class="rounded-full overflow-hidden w-10 h-10">
            <img class="w-full h-full" :src="getLastUserImage(user)" alt="User image">
        </div>
        <div>
            <h2 class="font-semibold">{{user.name}}</h2>
            <span class="text-[14px] text-[#92929D]">@{{user.name.split(' ').join('')}}</span>
        </div>
    </div>
    <div class="flex items-center gap-x-3">
        <button @click="addFriend" class="px-[15px] text-white text-[12px] font-semibold rounded-[10px] py-[11px] bg-[#0062FF]" type="button">Send Friend Request</button>
        <button v-if="following" @click="followOrUnfollow" @mouseover="followingText = 'Unfollow'" @mouseout="followingText = getFollowState()" class="px-[15px] text-white text-[12px] font-semibold rounded-[10px] py-[11px] bg-[#0062FF] hover:bg-red-700" type="button">{{followingText}}</button>
        <button v-else @click="followOrUnfollow" class="px-[15px] text-white text-[12px] font-semibold rounded-[10px] py-[11px] bg-[#0062FF]" type="button">{{followingText}}</button>
    </div>
</template>

<script setup>

import {router} from "@inertiajs/vue3";
import {ref} from "vue";
import {getLastUserImage} from "../utils/getImages.js";

const props = defineProps({
    user: Object
})

const following = ref(props.user.follow);
const followingText = ref(getFollowState());

function getFollowState() {
    return following.value ? 'Following' : 'Follow';
}

function addFriend() {
    router.post('http://127.0.0.1:8000/friends', {
        'friend_id': props.user.id
    })
}

function followOrUnfollow() {
    router.post(`http://127.0.0.1:8000/followers`, {
        'followed_id': props.user.id
    });

    following.value = !following.value
    followingText.value = getFollowState()
}

</script>
