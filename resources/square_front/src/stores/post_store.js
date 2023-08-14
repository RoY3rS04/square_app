import {defineStore} from "pinia";
import {ref} from 'vue';
import {router} from "@inertiajs/vue3";

export const usePostsStore = defineStore('post_store', () => {

    const posts = ref([]);

    async function getPosts() {
        const resp = await fetch('http://127.0.0.1:8000/home');

        posts.value = resp.data.posts;
    }

    getPosts();

    return {
        posts,
        getPosts
    }
});
