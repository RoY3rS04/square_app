<template>
    <section class="font-[Poppins] min-h-screen max-h-screen overflow-y-hidden flex flex-col">
        <header class="px-[25px] pt-[27px] pb-[20px] flex items-center justify-between border-b-[1px]">
            <div class="flex items-center gap-x-3 text-[20px] text-[#44444F] font-semibold">
                <div class="relative w-7 h-7">
                    <svg class="absolute" xmlns="http://www.w3.org/2000/svg" width="28px" height="27px" viewBox="0 0 42 43" fill="none">
                        <rect x="3" y="3.5" width="36" height="36" rx="12" stroke="#0073FF" stroke-width="6"/>
                    </svg>
                    <svg class="absolute top-[3.5px] right-[3.5px]" xmlns="http://www.w3.org/2000/svg" width="14" height="13" viewBox="0 0 25 24" fill="none">
                        <rect x="3.5" y="3" width="18" height="18" rx="9" stroke="#0073FF" stroke-width="6"/>
                    </svg>
                </div>
                Square
            </div>
            <div class="flex items-center gap-x-[25px]">
                <Link href="/notifications">
                    <svg @click="removeNotifications" xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24" fill="none">
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M14.98 4.64094C14.8026 3.15337 13.5367 2 12.0013 2C10.466 2 9.2 3.15337 9.02257 4.64094C6.5199 5.77546 4.77908 8.29555 4.77908 11.2222V16.2399L3.10858 19.5494C2.77286 20.2145 3.25626 21 4.0013 21H9.17201C9.58385 22.1652 10.6951 23 12.0013 23C13.3075 23 14.4188 22.1652 14.8306 21H20.0013C20.7463 21 21.2297 20.2145 20.894 19.5494L19.2235 16.2399V11.2222C19.2235 8.29555 17.4827 5.77546 14.98 4.64094ZM17.3308 16.9286L18.3764 19H5.62623L6.6718 16.9286C6.74234 16.7888 6.77908 16.6345 6.77908 16.478V11.2222C6.77908 8.33807 9.11715 6 12.0013 6C14.8855 6 17.2235 8.33807 17.2235 11.2222V16.478C17.2235 16.6345 17.2603 16.7888 17.3308 16.9286Z" fill="#92929D"/>
                        <path fill-rule="evenodd" clip-rule="evenodd" d="M19 8C21.2091 8 23 6.20914 23 4C23 1.79086 21.2091 0 19 0C16.7909 0 15 1.79086 15 4C15 6.20914 16.7909 8 19 8Z" :fill="getNotifications"/>
                    </svg>
                </Link>
                <div class="w-[34px] h-[34px] overflow-hidden rounded-full">
                    <a href="#">
                        <img class="w-full h-full" :src="getLastUserImage(userInfo)"  alt="User image">
                    </a>
                </div>
                <div class="relative cursor-pointer" @click="dropdown = !dropdown">
                    <svg class="ml-[-12px]" xmlns="http://www.w3.org/2000/svg" width="11" height="8" viewBox="0 0 11 8" fill="none">
                        <g id="ic_Dropdown">
                            <path id="Rectangle" fill-rule="evenodd" clip-rule="evenodd" d="M9.98762 0.461304C10.2559 0.461304 10.4051 0.771682 10.2375 0.981206L5.44196 6.97562C5.31386 7.13575 5.07031 7.13575 4.94221 6.97562L0.146672 0.981206C-0.0209475 0.771682 0.128228 0.461304 0.39655 0.461304L9.98762 0.461304Z" fill="#92929D"/>
                        </g>
                    </svg>
                    <div v-if="dropdown" class="absolute left-[-80px] top-12 py-3 px-5 bg-white rounded z-10">
                        <div class="flex flex-col gap-y-3">
                            <button @click="logout" class="text-[12px]" type="button">Sign Off</button>
                            <button @click="deleteModal = true" class="text-[12px] text-red-600" type="button">Remove Account</button>
                        </div>
                    </div>
                </div>
            </div>
        </header>
        <div class="flex flex-1">
            <aside class="w-[250px] px-[10px]">
                <Link href="/profile" class="w-full p-3 bg-[#FAFAFB] border border-[#E2E2EA] rounded-[15px] flex items-center gap-x-[10px] my-5 hover:border-[#0073FF] transition-[border] duration-300">
                    <div class="w-[34px] h-[34px] overflow-hidden rounded-full">
                        <img class="w-full h-full" :src="getLastUserImage(userInfo)"  alt="User image">
                    </div>
                    <div>
                        <h2 class="text-[14px] font-semibold">{{userInfo.name}}</h2>
                        <p class="text-[#92929D] text-[12px]">@{{userInfo.name.split(' ').join('')}}</p>
                    </div>
                </Link>
                <Navigation></Navigation>
            </aside>
            <main class="flex-1 h-screen bg-[#FAFAFB] overflow-y-scroll overflow-x-hidden">
                <div class="flex h-full justify-center">
                    <slot></slot>
                    <Modal v-if="deleteModal" :handle-close="() => {deleteModal = false}" title="Remove Your Account">
                        <h3 class="text-[18px] font-semibold text-center">Are you sure you really want to delete your account?
                            <br><span class="text-sm text-[#92929D] font-normal">If yes please enter your password and press the delete button</span></h3>
                        <form @submit.prevent="deleteAccount" class="mt-5 flex flex-col gap-y-3">
                            <Input name="password" placeholder="Please enter your password" type="password">
                                <font-awesome-icon icon="fa-regular fa-eye"></font-awesome-icon>
                            </Input>
                                <button class="py-3 px-5 bg-red-600 text-white rounded-[15px]" type="submit">Delete your Account</button>
                        </form>
                    </Modal>
                </div>
            </main>
        </div>
        <div v-if="createPost || seeComments || editProfile || deleteModal || showDeletePostModal || editPost" class="absolute top-0 right-0 left-0 bottom-0 bg-[#171725] z-1 opacity-30"></div>
    </section>
</template>

<script setup>
import Navigation from "./Navigation.vue";
import {Link, router} from "@inertiajs/vue3";
import {computed, onMounted} from "vue";
import {provide} from "vue";
import {getLastUserImage} from "../utils/getImages.js";
import {ref} from 'vue';
import Modal from "./Modal.vue";
import Input from "./Input.vue";
import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";

const props = defineProps({
    userInfo: Object,
    createPost: Boolean,
    notifications: Boolean,
    seeComments: Boolean,
    editProfile: Boolean,
    showDeletePostModal: Boolean,
    editPost: Boolean
})

provide('user', props.userInfo);

const getNotifications = computed(() => {
    if(props.notifications) {
        return '#FC5A5A'
    } else {
        return 'none';
    }
})

const dropdown = ref(false);
const deleteModal = ref(false);

function removeNotifications() {
    localStorage.setItem('notifications', 'false');
}

function logout() {
    router.post('http://127.0.0.1:8000/logout');
}

function deleteAccount(e) {
    const data = new FormData(e.target);

    router.post(`http://127.0.0.1:8000/users/${props.userInfo.id}`,{
        _method: 'DELETE',
        'password': data.get('password')
    })

    e.target.reset();
}

onMounted(() => {

})

</script>
