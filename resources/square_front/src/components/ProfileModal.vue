<template>
    <Modal v-if="editProfile" :handle-close="handleClose" title="Edit Your Profile">
        <form @submit.prevent="handleSubmit" enctype="multipart/form-data" class="flex flex-wrap gap-4">
            <div class="flex">
                <div class="flex flex-col items-center">
                    <h1 class="font-semibold text-[18px]">{{user.name}}</h1>
                    <div class="relative">
                        <div class="w-[100px] h-[100px] overflow-hidden rounded-full">
                            <img class="w-full h-full object-center object-cover" :src="userImage ?? getLastUserImage(user)" alt="User image">
                            <input @input="displayImages" id="user_image" name="user_image" type="file" hidden>
                        </div>
                        <label class="absolute bottom-1 right-1 cursor-pointer" for="user_image">
                            <PhotoIcon></PhotoIcon>
                        </label>
                    </div>
                </div>
            </div>
            <div class="flex-1 flex flex-col gap-y-3">
                <div class="grid grid-cols-2 gap-x-3">
                    <div>
                        <label class="text-sm" for="user_name">User Name</label>
                        <Input id="user_name" name="user_name" placeholder="Update your username" :value="user.name"/>
                    </div>
                    <div>
                        <label class="text-sm">Background Image</label>
                        <input @input="displayImages" type="file" hidden name="user_background" id="user_background" placeholder="Update your username"/>
                        <div class="rounded relative h-12 overflow-hidden">
                            <img class="w-full" :src="backgroundImage ?? getLastBackgroundImage(user)" alt="user background_image">
                            <label class="absolute bottom-1 right-1 cursor-pointer" for="user_background">
                                <PhotoIcon color="white"></PhotoIcon>
                            </label>
                        </div>
                    </div>
                </div>
                <div>
                    <label class="text-sm" for="user_description">Description</label>
                    <Input :value="user.description" type="textarea" my-class="w-full border rounded p-3 max-h-[80px] bg-[#F1F1F5] text-sm" id="user_description" name="user_description" placeholder="Add a description about yourself"/>
                </div>
            </div>
            <div class="w-full">
                <h3 class="text-[14px] font-medium text-[#171725] mt-[-15px]">Aditional Info (Not Required)</h3>
                <hr class="my-2">
                <div class="grid grid-cols-2 gap-3">
                    <div class="">
                        <label class="text-sm" for="user_location">Your Location</label>
                        <Input id="user_location" name="user_location" placeholder="Add your location" :value="user.location"/>
                    </div>
                    <div class="">
                        <label class="text-sm" for="user_web">Your Website</label>
                        <Input id="user_web" name="user_web" placeholder="Add your website" :value="user.website"/>
                    </div>
                    <div class="">
                        <label class="text-sm" for="user_company">Your Workplace</label>
                        <Input id="user_company" name="user_company" placeholder="Add your workplace" :value="user.workplace"/>
                    </div>
                    <div class="">
                        <label class="text-sm" for="user_relation">Relationship</label>
                        <Input id="user_relation" name="user_relation" placeholder="Add your relation with someone here" :value="user.relation"/>
                    </div>
                </div>
            </div>
            <button class="w-full text-white font-semibold bg-[#0062FF] rounded-[15px] px-5 py-3" type="submit">
                Submit
            </button>
        </form>
    </Modal>
</template>

<script setup>

import Modal from "./Modal.vue";
import PhotoIcon from "./icons/PhotoIcon.vue";
import Input from "./Input.vue";
import {ref} from 'vue';
import {router} from "@inertiajs/vue3";

const props = defineProps({
    editProfile: Boolean,
    handleClose: Function,
    user: Object
})

const userImage = ref(null);
const backgroundImage = ref(null);

function displayImages(e) {
    if(e.target.name === 'user_image') {
        userImage.value = URL.createObjectURL(e.target.files[0]);
    } else {
        backgroundImage.value = URL.createObjectURL(e.target.files[0]);
    }
}

function getLastUserImage(user) {
    const images = user.media.filter((image) => {
        if(image.collection_name === 'user_images') {
            return image;
        }
    })

    return images[images.length - 1].original_url;
}

function getLastBackgroundImage(user) {
    const images = user.media.filter((image) => {
        if(image.collection_name === 'user_backgrounds') {
            return image;
        }
    })

    return images[images.length - 1].original_url;
}

function handleSubmit(e) {
    const data = new FormData(e.target);

    router.post(`http://127.0.0.1:8000/users/${props.user.id}`, {
        _method: 'patch',
        'user_image': data.get('user_image'),
        'name': data.get('user_name'),
        'user_background': data.get('user_background'),
        'description': data.get('user_description'),
        'location': data.get('user_location'),
        'website': data.get('user_web'),
        'workplace': data.get('user_company'),
        'relation': data.get('user_relation')
    })

    userImage.value = null;
    backgroundImage.value = null;
}

</script>
