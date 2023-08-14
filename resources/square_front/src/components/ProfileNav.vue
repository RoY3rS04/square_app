<template>
    <nav>
        <ul class="flex items-center">
            <li>
                <NavigationLink href="/profile" linkTitle="Posts" my-link-style="py-[10px] px-[15.5px] text-[14px] font-bold text-[#92929D]" :activePrimaryBorderStyle="false">
                    <template #extraInfo>
                        {{posts.length}}
                        <div :data-active="page.url.includes('/profile') ? true : undefined" class="w-[69px] hidden h-1 data-[active]:block bg-[#0073FF]"></div>
                    </template>
                </NavigationLink>
            </li>
            <li>
                <NavigationLink href="#" linkTitle="Followers" my-link-style="py-[10px] px-[15.5px] text-[14px] font-bold text-[#92929D]" :activePrimaryBorderStyle="false">
                    <template #extraInfo>
                        {{followers.length}}
                        <div :data-active="page.url.includes('#') ? true : undefined" class="w-[69px] hidden h-1 data-[active]:block bg-[#0073FF]"></div>
                    </template>
                </NavigationLink>
            </li>
            <li>
                <NavigationLink href="#" linkTitle="Following" my-link-style="py-[10px] px-[15.5px] text-[14px] font-bold text-[#92929D]" :activePrimaryBorderStyle="false">
                    <template #extraInfo>
                        {{following.length}}
                        <div :data-active="page.url.includes('#') ? true : undefined" class="w-[69px] hidden h-1 data-[active]:block bg-[#0073FF]"></div>
                    </template>
                </NavigationLink>
            </li>
            <li>
                <NavigationLink href="#" linkTitle="Likes" my-link-style="py-[10px] px-[15.5px] text-[14px] font-bold text-[#92929D]" :activePrimaryBorderStyle="false">
                    <template #extraInfo>
                        {{likes}}
                        <div :data-active="page.url.includes('#') ? true : undefined" class="w-[69px] hidden h-1 data-[active]:block bg-[#0073FF]"></div>
                    </template>
                </NavigationLink>
            </li>
            <li>
                <NavigationLink href="#" linkTitle="Photos" my-link-style="py-[10px] px-[15.5px] text-[14px] font-bold text-[#92929D]" :activePrimaryBorderStyle="false">
                    <template #extraInfo>
                        {{media.length + postsFotos.length}}
                        <div :data-active="page.url.includes('#') ? true : undefined" class="w-[69px] hidden h-1 data-[active]:block bg-[#0073FF]"></div>
                    </template>
                </NavigationLink>
            </li>
            <li>
                <NavigationLink href="#" linkTitle="Videos" my-link-style="py-[10px] px-[15.5px] text-[14px] font-bold text-[#92929D]" :activePrimaryBorderStyle="false">
                    <template #extraInfo>
                        {{userVideos}}
                        <div :data-active="page.url.includes('#') ? true : undefined" class="w-[69px] hidden h-1 data-[active]:block bg-[#0073FF]"></div>
                    </template>
                </NavigationLink>
            </li>
            <li>
                <NavigationLink href="#" linkTitle="Saved" my-link-style="py-[10px] px-[15.5px] text-[14px] font-bold text-[#92929D]" :activePrimaryBorderStyle="false">
                    <template #extraInfo>
                        {{saveds.length}}
                        <div :data-active="page.url.includes('#') ? true : undefined" class="w-[69px] hidden h-1 data-[active]:block bg-[#0073FF]"></div>
                    </template>
                </NavigationLink>
            </li>
        </ul>
    </nav>
</template>

<script setup>

    import NavigationLink from "./NavigationLink.vue";
    import {usePage} from "@inertiajs/vue3";
    import {inject, ref} from "vue";

    const page = usePage();

    const props = defineProps({
        userInfo: Object
    })

    const {
        posts = 0,
        followers = 0,
        following = 0,
        media = 0,
        userVideos = 0,
        saveds = 0
    } = props.userInfo;

    let postsFotos = [];

    posts.forEach((post) => {
        postsFotos = [
            ...postsFotos,
            ...post.media
        ]
    })

    const likes = inject('likes');
    console.log(likes);

</script>
