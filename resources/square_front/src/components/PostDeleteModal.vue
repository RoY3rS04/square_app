<template>
    <Modal v-if="deletePost" title="Delete your post" :handle-close="handleClose">
        <h3 class="text-[18px] font-semibold text-center">Are you sure you want to delete this post?
            <br><span class="text-sm text-[#92929D] font-normal">If yes please click the button below</span></h3>
        <button @click="removePost" class="py-3 mt-4 px-5 bg-red-600 text-white rounded-[15px]" type="submit">Delete Post</button>
    </Modal>
</template>

<script setup>
import Modal from "./Modal.vue";
import {router} from "@inertiajs/vue3";

const props = defineProps({
    deletePost: Boolean,
    handleClose: Function,
    id: Number
})

function removePost() {
    router.delete(`http://127.0.0.1:8000/posts/${props.id}`)
}

</script>
