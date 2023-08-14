<template>
    <div class="rounded-[15px] bg-white p-[15px] w-[625px]">
        <div class="flex justify-between items-center">
            <div class="flex items-center gap-x-[10px]">
                <div class="w-[42px] h-[42px] overflow-hidden rounded-full">
                    <img class="w-full h-full" :src="getUserImage"  alt="User image">
                </div>
                <div class="flex flex-col">
                    <h2 class="text-[14px] text-[#171725] font-semibold">{{postInfo?.user?.name ?? authUser.name}}</h2>
                    <p class="text-[12px] text-[#92929D]">{{timeAgo}}</p>
                </div>
            </div>
            <MoreOptions>
                <div class="space-y-4" v-if="page.url.includes('profile')">
                    <div @click="() => {
                        showDelete();
                        $emit('postId', postInfo.id);
                    }" class="flex gap-x-3 cursor-pointer items-center">
                        <TrashIcon color="#92929D"></TrashIcon>
                        <span class="text-sm text-[#44444F]">Remove this post</span>
                    </div>
                    <div @click="() => {
                        showEdit();
                        $emit('editId', postInfo.id);
                    }" class="flex gap-x-3 cursor-pointer items-center">
                        <EditIcon color="#92929D"></EditIcon>
                        <span class="text-sm text-[#44444F]">Edit this post</span>
                    </div>
                </div>
                <div v-else class="space-y-4">
                    <div class="flex gap-x-3 cursor-pointer">
                        <SavedIcon color="#92929D"></SavedIcon>
                        <div class="flex flex-col gap-y-1.5">
                            <h3 class="text-sm text-[#44444F]">Save Link</h3>
                            <span class="text-[#92929D] text-[12px]">Add this to your saved items</span>
                        </div>
                    </div>
                    <div @click="handleHideClick" class="flex gap-x-3 cursor-pointer">
                        <HideIcon color="#B5B5BE"></HideIcon>
                        <div class="flex flex-col gap-y-1.5">
                            <h3 class="text-sm text-[#44444F]">Hide all from "{{postInfo.user.name}}"</h3>
                            <span class="text-[#92929D] text-[12px]">Stop seeing posts from this person</span>
                        </div>
                    </div>
                    <div @click="handleUnfollowClick" class="flex gap-x-3 cursor-pointer">
                        <UserIcon color="#92929D"></UserIcon>
                        <div class="flex flex-col gap-y-1.5">
                            <h3 class="text-sm text-[#44444F]">Unfollow "{{postInfo.user.name}}"</h3>
                            <span class="text-[#92929D] text-[12px]">Disconnect with this person</span>
                        </div>
                    </div>
                </div>
            </MoreOptions>
        </div>
        <div class="mt-5 flex flex-col gap-y-5">
            <p class="text-[#44444F] text-[14px] leading-[24px]">{{postInfo.description}}</p>
            <div v-if="postInfo.media.length > 0" class="grid grid-cols-2 gap-[15px]">
                <div v-for="(postImage,index) in postInfo.media" class="rounded-[15px] overflow-hidden" :class="{'col-start-1 col-end-3': postInfo.media.length % 2 !== 0 && index === postInfo.media.length - 1}">
                    <img class="w-full h-full object-cover object-center" :src="postImage.original_url" alt="Post image">
                </div>
            </div>
            <hr>
            <Options @fetchComments="(id) => $emit('fetchComments', id)" :postStats="postInfo"></Options>
            <hr>
            <CommentBox :user="authUser" :postInfo="postInfo"></CommentBox>
        </div>
    </div>
</template>

<script setup>
    import MoreOptions from "./MoreOptions.vue";
    import Options from "./Options.vue";
    import CommentBox from "./CommentBox.vue";
    import SavedIcon from "./icons/SavedIcon.vue";
    import HideIcon from "./icons/HideIcon.vue";
    import UserIcon from "./icons/UserIcon.vue";
    import {computed, inject, provide, ref} from "vue";
    import {router, usePage} from "@inertiajs/vue3";
    import {getLastUserImage} from "../utils/getImages.js";
    import TrashIcon from "./icons/TrashIcon.vue";
    import EditIcon from "./icons/EditIcon.vue";
    import PostDeleteModal from "./PostDeleteModal.vue";
    import getTimeAgo from "../utils/getTimeAgo.js";

    const props = defineProps({
        postInfo: Object,
        authUser: Object
    })

    const timeAgo = ref(getTimeAgo(new Date(props.postInfo.created_at)));

    setInterval(() => {
        timeAgo.value = getTimeAgo(new Date(props.postInfo.created_at));
    }, 60000);

    const page = usePage();

    const getUserImage = computed(() => {
        if (page.url.includes('/profile')) {
            return getLastUserImage(props.authUser);
        } else {
            return getLastUserImage(props.postInfo.user);
        }
    })

    let showDelete, showEdit;

    if(page.url.includes('profile')) {
        const {showDeletePostModal} = inject('showDeletePostModal');

        const {showEditPostModal} = inject('showEditPostModal');

        showEdit = showEditPostModal;
        showDelete = showDeletePostModal;
    }

    const removePersonPosts = inject('removePersonPosts');
    provide('post',props.postInfo);

    function handleHideClick() {
        if(!page.url.includes('/profile')) {
            removePersonPosts(props.postInfo.user.id);

            router.post(`http://127.0.0.1:8000/followers/posts/${props.postInfo.user.id}`)
        }
    }

    function handleUnfollowClick() {
        router.delete(`http://127.0.0.1:8000/followers/${props.postInfo.user.id}`);
    }

</script>
