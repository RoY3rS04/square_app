<template>
    <Suspense>
        <Modal v-if="editPost" title="Edit your post" :handle-close="handleEditClose" my-class="mt-[-60px]">
            <form @submit.prevent="handleSubmit" class="flex flex-col gap-y-3" enctype="multipart/form-data">
                <div>
                    <div class="flex items-center justify-between mb-3">
                        <label for="description">Description</label>
                        <div>
                            <button class="text-white bg-[#0062FF] rounded-[10px] text-[12px] font-semibold py-[10px] px-[25px]" type="submit">Update Post</button>
                        </div>
                    </div>
                    <Input type="textarea" my-class="w-full border rounded p-3 max-h-[80px] bg-[#F1F1F5] text-sm" id="description" name="description" placeholder="Your new post description" :value="post.description"/>
                </div>
                <div>
                    <h3 class="font-medium mb-2">Older images</h3>
                    <div class="grid grid-cols-2 gap-4 max-h-[150px] overflow-y-scroll">
                        <div v-for="image in post.media" class="rounded-[15px] relative overflow-hidden">
                            <img class="object-center object-cover w-full h-full" :src="image.original_url ?? image" alt="Post image">
                            <div @click="deleteFromPost(image.id)" class="w-8 h-8 rounded-full bg-white absolute top-0 right-0 flex items-center justify-center cursor-pointer font-bold text-red-600">X</div>
                        </div>
                    </div>
                    <div class="flex items-center justify-between">
                        <h3 class="font-medium my-2">New images</h3>
                        <div>
                            <label class="cursor-pointer" for="post_images">Add new images</label>
                            <input @input="showImages" id="post_images" name="post_images" type="file" hidden multiple/>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4 max-h-[150px] overflow-y-scroll">
                        <div v-for="image in postMedia" class="rounded-[15px] relative overflow-hidden">
                            <img class="object-center object-cover w-full h-full" :src="image.original_url ?? image" alt="Post image">
                            <div @click="deleteFromPost(image, true)" class="w-8 h-8 rounded-full bg-white absolute top-0 right-0 flex items-center justify-center cursor-pointer font-bold text-red-600">X</div>
                        </div>
                    </div>
                </div>
            </form>
        </Modal>
    </Suspense>
</template>

<script setup>

import Modal from "./Modal.vue";
import Input from "./Input.vue";
import {ref} from 'vue';
import {router} from "@inertiajs/vue3";

const props = defineProps({
    editPost: Boolean,
    handleClose: Function,
    post: Object
})

const postMedia = ref([]);
const deletedMedia = ref([]);
const newMedia = ref([]);

function handleEditClose() {
    props.handleClose();
    postMedia.value = [];
    deletedMedia.value = [];
    newMedia.value = [];
}

function deleteFromPost(id, newImages = false) {
    if(newImages) {
        newMedia.value = newMedia.value.filter((media) => {
            if(media.id !== id) {
                return media;
            }
        })
        postMedia.value = postMedia.value.filter((media) => {
            if(media !== id) {
                return media;
            }
        })
    } else {
        props.post.media = props.post.media.filter((media) => {
            if(media.id !== id) {
                return media;
            } else {
                deletedMedia.value.push(media.id);
            }
        })
    }
}

function showImages(e) {

    newMedia.value = [...newMedia.value, ...e.target.files];

    postMedia.value = [
        ...postMedia.value,
        ...newMedia.value.map((file) => {
        return URL.createObjectURL(file)})
    ];
}

function handleSubmit(e) {
    const data = new FormData(e.target);

    router.post(`http://127.0.0.1:8000/posts/${props.post.id}`, {
        _method: 'PATCH',
        'newImages': newMedia.value,
        description: data.get('description'),
        'deletedImages': deletedMedia.value.length > 0 ? deletedMedia.value : null
    })
}

</script>
