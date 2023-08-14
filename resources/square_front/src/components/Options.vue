<template>
    <div class="flex items-center gap-x-12">
        <form>
            <button @click="() => {
                seeComments();
                $emit('fetchComments', post.id)
            }" type="button" class="flex items-center gap-x-[10px] text-[#44444F] text-[14px]">
                <CommentIcon color="#92929D"></CommentIcon>
                {{post.comments.length}} Comments</button>
        </form>
        <form @submit.prevent="handleLikeEvent">
            <button type="submit" class="flex items-center gap-x-[10px] text-[#44444F] text-[14px]">
                <font-awesome-icon v-if="youLikedIt" class="w-6 h-6 text-red-700" :icon="['fas','heart']"></font-awesome-icon>
                <HeartIcon v-else color="#92929D"></HeartIcon>
                {{post.likes.length}} Likes</button>
        </form>
        <form>
            <button @click="handleSave" type="button" class="flex items-center gap-x-[10px] text-[#44444F] text-[14px]">
                <SavedIcon color="#92929D"></SavedIcon>
                {{post.saveds.length}} Saved</button>
        </form>
    </div>
</template>

<script setup>

import CommentIcon from "./icons/CommentIcon.vue";
import HeartIcon from "./icons/HeartIcon.vue";
import SavedIcon from "./icons/SavedIcon.vue";
import {computed, inject, onMounted, onUnmounted} from "vue";
import {ref} from "vue";
import {router} from "@inertiajs/vue3";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

const user = inject('user');
const post = inject('post');
const {seeComments} = inject('seeComments');

const youLikedIt = ref(
    user.likes.filter((like) => {
    if(like.likeable_id === post.id) {
        return like;
    }
}).length > 0
);

function handleLikeEvent() {

    router.post('http://127.0.0.1:8000/likes', {
        'user_id': user.id,
        'likeable_id': post.id,
        'likeable_type': 'App\\Models\\Post'
    })

    youLikedIt.value = !youLikedIt.value;

    // fetch('http://127.0.0.1:8000/likes', {
    //     method: 'POST',
    //     headers: {
    //         'Content-Type': 'application/json',
    //         Accept: 'application/json',
    //         'X-CSRF-TOKEN': csrf
    //     },
    //     body: JSON.stringify({
    //         'user_id': user.id,
    //         'likeable_id': post.id,
    //         'likeable_type': 'App\\Models\\Post'
    //     })
    // })
}

function handleSave() {
    router.post('http://127.0.0.1:8000/saveds', {
        'user_id': user.id,
        'saveable_id': post.id,
        'saveable_type': 'App\\Models\\Post'
    });
}

</script>
