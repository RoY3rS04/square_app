<template>
    <div class="flex text-[#171725] gap-x-5 items-center data-[active]:text-[#0073FF] text-semibold" :data-active="page.url.includes(props.href) ? true : undefined">
        <div v-if="activePrimaryBorderStyle" :data-active="page.url.includes(props.href) ? true : undefined" class="absolute left-[-10px] h-[20px] w-[3px] data-[active]:bg-[#0073FF]" :class="activePrimaryBorderStyle"></div>
        <slot></slot>
        <div :data-active="page.url.includes(props.href) ? true : undefined" class="flex flex-col gap-y-1 items-center data-[active]:text-[#0073FF]" :class="myLinkStyle">
            <Link :href="href">
                {{linkTitle}}
            </Link>
            <div :data-active="page.url.includes(props.href) ? true : undefined" class="text-[16px] text-[#44444F] w-full flex flex-col items-center data-[active]:text-[#0073FF]">
                <slot name="extraInfo"></slot>
            </div>
        </div>
    </div>
</template>

<script setup>

    import {Link} from "@inertiajs/vue3";
    import {usePage} from "@inertiajs/vue3";

    const props = defineProps({
        linkTitle: String,
        href: String,
        myLinkStyle: String,
        activePrimaryBorderStyle: Boolean
    })
    const page = usePage();

</script>
