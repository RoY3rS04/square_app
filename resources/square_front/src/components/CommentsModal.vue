<template>
    <Modal v-if="seeComments" title="Comments" :handle-close="handleClose">
        <Suspense>
            <div class="space-y-5">
                <div class="max-h-[360px] flex flex-col gap-y-3 overflow-y-scroll">
                    <div v-for="comment in realComments">
                        <div class="flex items-center gap-x-3">
                            <div class="rounded-full w-8 h-8">
                                <img :src="getLastUserImage(comment.user)" alt="User image">
                            </div>
                            <div>
                                <h2 class="font-semibold">{{comment.user.name}} <span class="text-[12px] text-[#92929D] font-normal">{{comment.created_at}}</span></h2>
                                <p class="font-[Roboto] text-sm text-[#44444F]">{{comment.comment}}</p>
                            </div>
                        </div>
                    </div>
                </div>
                <CommentBox :user="user" my-class="max-h-auto overflow-y-none"></CommentBox>
            </div>

            <template #fallback>
                <div class="flex flex-col gap-y-3">
                    <div class="w-full h-[30px] rounded bg-[#92929D] animate-pulse"></div>
                </div>
            </template>
        </Suspense>
    </Modal>
</template>

<script setup>

import Modal from "./Modal.vue";
import {inject, ref} from 'vue';
import CommentBox from "./CommentBox.vue";
import {getLastUserImage} from "../utils/getImages.js";

const props = defineProps({
    seeComments: Boolean,
    handleClose: Function,
})

const user = inject('user');

const realComments = ref(inject('postComments'));
console.log('real comments ',realComments.value);

</script>
