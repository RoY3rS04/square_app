<template>
    <HomeLayout :userInfo="user" :notifications="notifications">
        <div class="flex flex-col gap-y-8 mt-[35px]">
            <div class="flex items-center justify-between">
                <h1 class="text-[#171725] text-[24px] font-semibold">Your Photos</h1>
                <div class="bg-white text-[14px] p-3 rounded-[10px] text-[#696974]">
                    <span>Sort by:</span>
                    <select class="bg-transparent text-[#44444F] font-medium w-[100px]">
                        <option class="flex items-center justify-between" value="Hello">Hello</option>
                    </select>
                </div>
            </div>
            <div class="grid grid-cols-3 gap-5 pb-[95px]">
                <div v-for="photo in myPhotos" class="rounded-[15px]  relative p-5 w-[316px] h-[200px] flex justify-between overflow-hidden text-white text-[14px] group isolate">
                    <img class="w-full z-[-1] absolute top-0 left-0 bottom-0 right-0 h-full object-cover object-center" :src="photo" alt="Post Image">
                    <div class="self-end z-1 opacity-0 group-hover:opacity-100 transition-[opacity] duration-500 flex items-center gap-x-1">
                        <button type="button">Like</button>
                        <span class="font-bold">·</span>
                        <Link href="#">Comment</Link>
                    </div>
                    <button class="self-end z-1 opacity-0 group-hover:opacity-100 duration-500" type="button">
                        <svg class="mt-[-38px]" xmlns="http://www.w3.org/2000/svg" width="29" height="28" viewBox="0 0 29 28" fill="none">
                            <path fill-rule="evenodd" clip-rule="evenodd" d="M16.0626 22.1265L16.518 21.7763C17.0298 21.3827 17.7648 21.4772 18.1596 21.9874C18.5544 22.4975 18.4596 23.2302 17.9478 23.6237L15.6269 25.4085C15.5563 25.4653 15.4803 25.513 15.4004 25.5513C15.3378 25.5828 15.2538 25.6136 15.166 25.6346C15.0531 25.6609 14.9447 25.6705 14.8371 25.6654C14.6721 25.6578 14.516 25.6161 14.3758 25.5473C14.2989 25.5097 14.2256 25.4634 14.1575 25.4085L11.8366 23.6237C11.3248 23.2302 11.23 22.4975 11.6248 21.9874C12.0196 21.4772 12.7545 21.3827 13.2663 21.7763L13.7218 22.1265V9.86666C13.7218 9.22233 14.2458 8.7 14.8922 8.7C15.5386 8.7 16.0626 9.22233 16.0626 9.86666V22.1265ZM15.4774 2.33337C18.4746 2.33337 21.0221 4.38793 21.7235 7.20113C24.5402 7.90252 26.5959 10.4465 26.5959 13.4377C26.5959 16.7215 24.118 19.4679 20.8729 19.8263C20.2304 19.8972 19.6519 19.4356 19.5808 18.7952C19.5096 18.1547 19.9727 17.5781 20.6152 17.5071C22.6768 17.2795 24.2551 15.53 24.2551 13.4377C24.2551 11.337 22.6644 9.58306 20.5926 9.36584C20.0429 9.30821 19.6086 8.87508 19.551 8.3271C19.3334 6.25551 17.5778 4.66671 15.4774 4.66671C13.375 4.66671 11.6186 6.25821 11.4032 8.33203C11.3361 8.97866 10.7517 9.44584 10.1039 9.37077C9.94613 9.35248 9.78652 9.34325 9.62551 9.34325C7.36375 9.34325 5.52922 11.1758 5.52922 13.4377C5.52922 15.53 7.1076 17.2795 9.16919 17.5071C9.81164 17.5781 10.2748 18.1547 10.2036 18.7952C10.1324 19.4356 9.55391 19.8972 8.91146 19.8263C5.66633 19.4679 3.18848 16.7215 3.18848 13.4377C3.18848 10.0042 5.88481 7.19858 9.27949 7.01905C10.0434 4.29992 12.545 2.33337 15.4774 2.33337Z" fill="#92929D"/>
                        </svg>
                    </button>
                </div>
            </div>
        </div>
    </HomeLayout>
</template>

<script setup>

import HomeLayout from "../../components/HomeLayout.vue";
import {Link, router} from "@inertiajs/vue3";
import {onMounted, onUnmounted, provide, ref} from "vue";
import echo from "../../utils/echo.js";
import removeDuplicatedItems from "../../utils/removeDuplicatedItems.js";
import {getLastUserImage} from "../../utils/getImages.js";

const props = defineProps({
    user: Object,
})

console.log(props.user);

const myPhotos = ref([
    ...props.user.media.map((photo) => photo.original_url)
]);

props.user.posts.forEach((post) => {
    myPhotos.value = [
        ...myPhotos.value,
        ...post.media.map((photo) => photo.original_url)
    ]
})

let notificationsListener;
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
