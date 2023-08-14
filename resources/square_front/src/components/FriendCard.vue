<template>
    <div class="rounded-[15px] w-[480px] pb-[50px] bg-white">
        <div class="rounded-tl-[15px] rounded-tr-[15px] h-[100px] overflow-hidden">
            <img class="w-full h-full object-center object-cover" alt="Background image" :src="backgroundImage">
        </div>
        <div class="flex gap-x-4 px-5 mt-[15px]">
            <div class="w-[86px] h-[86px] mt-[-33px] flex items-center justify-center bg-white rounded-full">
                <div class="w-[90%] h-[90%] rounded-full overflow-hidden">
                    <img class="w-full h-full" :src="userImage"  alt="User image">
                </div>
            </div>
            <div class="flex flex-col flex-1">
                <div class="flex items-center justify-between">
                    <h2 class="text-[#171725] text-[18px] font-semibold">{{user.name}}</h2>
                    <button v-if="following" @click="followOrUnfollow" @mouseover="followingText = 'Unfollow'" @mouseout="followingText = getFollowState()" class="font-semibold text-white px-[10px] py-[5px] bg-[#0062FF] text-[12px] rounded-[10px] hover:bg-red-700" type="button">{{followingText}}</button>
                    <button v-else @click="followOrUnfollow" class="font-semibold text-white px-[10px] py-[5px] bg-[#0062FF] text-[12px] rounded-[10px]" type="button">{{followingText}}</button>
                </div>
                <span class="text-[14px] text-[#92929D]">@{{user.name.split(' ').join('')}}</span>
                <p v-if="user.description" class="text-[#44444F] ">{{user.description}}</p>
            </div>
        </div>
    </div>
</template>

<script setup>

import {router} from "@inertiajs/vue3";
import {ref} from "vue";

const props = defineProps({
    userImage: String,
    backgroundImage: String,
    user: Object
})

const following = ref(props.user.follow);
const followingText = ref(getFollowState());

function getFollowState() {
    return props.user.follow ? 'Following' : 'Follow';
}


function followOrUnfollow() {
    router.post(`http://127.0.0.1:8000/followers`, {
        'followed_id': props.user.id
    });
}

</script>
