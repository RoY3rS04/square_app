<template>
    <Suspense>
        <ProfileLayout :user="user" :notifications="notifications" :create-post="createPost" :see-comments="seeComments" :edit-profile="editProfile" :delete-post="deletePost" :edit-post="editPost">
            <div class="flex flex-col gap-y-5">
                <div class="bg-white flex flex-col rounded-[15px] w-[625px]">
                    <div class="text-[14px] border-b-[1px] font-medium py-[10px] px-5">Post Something</div>
                    <div class="flex items-center p-[15px] justify-between">
                        <div class="flex items-center gap-x-5">
                            <div class="w-[36px] h-[36px] overflow-hidden rounded-full">
                                <img class="w-full h-full" :src="getLastUserImage(user)"  alt="User image">
                            </div>
                            <button @click="createPost = true" class="text-[#92929D] text-[14px]" type="button">What's on your mind?</button>
                        </div>
                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M22.5 18.65C22.5 20.5061 20.9274 22 19 22H5C3.07258 22 1.5 20.5061 1.5 18.65V5.35C1.5 3.49395 3.07258 2 5 2H19C20.9274 2 22.5 3.49395 22.5 5.35V18.65ZM21.5 15.0708L16.7865 10.775L12.0725 15.0284L17.4604 21H19C20.3863 21 21.5 19.942 21.5 18.65V15.0708ZM21.5 13.7178V5.35C21.5 4.05803 20.3863 3 19 3H5C3.61372 3 2.5 4.05803 2.5 5.35V17.445L8.31356 11.646C8.51602 11.444 8.84633 11.4527 9.0379 11.6651L11.4026 14.2859L16.4533 9.72878C16.6443 9.55643 16.9349 9.55716 17.125 9.73045L21.5 13.7178ZM16.1135 21L8.64759 12.7253L2.50884 18.8487C2.61604 20.0487 3.68548 21 5 21H16.1135ZM8.19048 8.7C7.12564 8.7 6.2619 7.83843 6.2619 6.775C6.2619 5.71157 7.12564 4.85 8.19048 4.85C9.25532 4.85 10.119 5.71157 10.119 6.775C10.119 7.83843 9.25532 8.7 8.19048 8.7ZM8.19048 7.7C8.70359 7.7 9.11905 7.28558 9.11905 6.775C9.11905 6.26442 8.70359 5.85 8.19048 5.85C7.67736 5.85 7.2619 6.26442 7.2619 6.775C7.2619 7.28558 7.67736 7.7 8.19048 7.7Z" fill="#92929D"/>
                        </svg>
                    </div>
                </div>
                <div class="flex flex-col gap-y-5">
                    <Post @fetchComments="(id) => {
                    fetchPostComments(id);
                    postId = id;
                }" @postId="(id) => deleteId = id"
                          @editId="(id) => {
                        fetchPost(id);
                      }" v-for="post in realPosts" :authUser="user" :postInfo="post">
                    </Post>
                </div>
            </div>
            <PostModal :create-post="createPost" :handle-modal-close="handleModalClose" :user="user"></PostModal>
            <CommentsModal :see-comments="seeComments" :handle-close="handleCommentsClose"></CommentsModal>
            <ProfileModal :handle-close="handleEditClose" :edit-profile="editProfile" :user="user"></ProfileModal>
            <PostDeleteModal :delete-post="deletePost" :handle-close="handleClose" :id="deleteId"></PostDeleteModal>
            <EditPostModal :edit-post="editPost" :handle-close="handleEditPostClose" :post="postToEdit"></EditPostModal>
        </ProfileLayout>
    </Suspense>
</template>

<script setup>

import ProfileLayout from "../../components/ProfileLayout.vue";
import Input from "../../components/Input.vue";
import Modal from "../../components/Modal.vue";
import Post from "../../components/Post.vue";
import {onMounted, onUnmounted, provide, ref} from "vue";
import PostModal from "../../components/PostModal.vue";
import echo from "../../utils/echo.js";
import {router} from "@inertiajs/vue3";
import removeDuplicatedItems from "../../utils/removeDuplicatedItems.js";
import CommentsModal from "../../components/CommentsModal.vue";
import ProfileModal from "../../components/ProfileModal.vue";
import {getLastUserImage} from "../../utils/getImages.js";
import PostDeleteModal from "../../components/PostDeleteModal.vue";
import EditPostModal from "../../components/EditPostModal.vue";

const createPost = ref(false);

const seeComments = ref(false);

const editProfile = ref(false);

const deletePost = ref(false);

const editPost = ref(false);

function handleClose() {
    deletePost.value = false;
}

function handleEditPostClose() {
    editPost.value = false;
}

const postId = ref(0);
const deleteId = ref(0);
const postToEdit = ref({});

provide('postId', postId);

provide('seeComments',{
    seeComments() {
        seeComments.value = true;
    }
});

provide('showEditPostModal',{
    showEditPostModal() {
        editPost.value = true;
    }
});

provide('showDeletePostModal',{
    showDeletePostModal() {
        deletePost.value = true;
    }
});

provide('showEdit',{
    showEdit() {
        editProfile.value = true;
    }
});

const postComments = ref([]);
provide('postComments', postComments);
provide('removePersonPosts', removePersonPosts);
provide('deletePost', deletePost.value);

function removePersonPosts(id) {
    realPosts.value = realPosts.value.filter((post) => {
        if(post.user.id !== id) {
            return post;
        }
    })
}

async function fetchPostComments(id) {
    const resp = await fetch(`http://127.0.0.1:8000/posts/${id}`);

    const data = await resp.json();

    postComments.value = data.comments;
}

async function fetchPost(id) {
    const resp = await fetch(`http://127.0.0.1:8000/posts/edit/${id}`);

    postToEdit.value = await resp.json();
}

function handleCommentsClose() {
    seeComments.value = false;
}

function handleModalClose() {
    createPost.value = false;
}

function handleEditClose() {
    editProfile.value = false;
}

const props = defineProps({
    user: Object,
})

const realPosts = ref(props.user.posts);
let likesListener;
const likes = ref(0);

props.user.posts.forEach((post) => {
    likes.value += post.likes.length;
});

let notificationsListener, commentsListener, savedListener;
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
    likesListener = echo.channel('likes').listen('LikeEvent', (likeable) => {
        const {like, wasCreated} = likeable.likeable;

        realPosts.value = realPosts.value.map((post) => {
            if(post.id === like.likeable.id) {
                if(wasCreated) {
                    post.likes = [
                        ...post.likes,
                        like
                    ];
                    likes.value++;
                } else {
                    post.likes = post.likes.filter((actuaLike) => {
                        if(actuaLike.id !== like.id) {
                            return actuaLike;
                        }
                    });
                    likes.value--;
                }
            }

            return post;
        })
    })

    shouldNotify.value.forEach((friend) => {
        notificationsListener = echo.channel(`notifications-${friend.id}`)
            .listen('NotificationCreated', (notification) => {

                localStorage.setItem('notifications', 'true');
                notifications.value = true;
            })
    })

    commentsListener = echo.channel('comments').listen('CommentCreated', (comment) => {
        postComments.value = [
            ...postComments.value,
            comment.comment
        ]

        realPosts.value = realPosts.value.map((post) => {
            if(post.id === comment.comment.commentable_id) {
                post.comments = [
                    ...post.comments,
                    comment.comment
                ]
            }
            return post;
        })
    })

    savedListener = echo.channel('saved').listen('SavedCreated', ({saved}) => {
        const {saved: savedItem, wasSaved} = saved;

        realPosts.value = realPosts.value.map((post) => {
            if(post.id === savedItem.saveable.id) {
                if(wasSaved) {
                    post.saveds = [
                        ...post.saveds,
                        savedItem
                    ];
                } else {
                    post.saveds = post.saveds.filter((actualSave) => {
                        if(actualSave.id !== savedItem.id) {
                            return actualSave;
                        }
                    });
                }
            }

            return post;
        })
    })
});

onUnmounted(() => {
    likesListener.stopListening('LikeEvent');
    notificationsListener?.stopListening('NotificationCreated');
    commentsListener.stopListening('CommentCreated');
});

provide('likes', likes);

</script>
