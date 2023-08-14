<template>
    <div class="flex flex-col gap-y-1">
        <div @click="handleOnClick" class="bg-[#F1F1F5] rounded-[7px] flex items-center w-[34px] h-[14px] justify-center gap-x-1 self-end cursor-pointer" :class="myClass">
            <div class="bg-[#92929D] w-1 h-1 rounded-full"></div>
            <div class="bg-[#92929D] w-1 h-1 rounded-full"></div>
            <div class="bg-[#92929D] w-1 h-1 rounded-full"></div>
        </div>
        <div class="relative">
            <div v-if="isOpen" class="absolute z-1 top-0 right-0 rounded-[8px] bg-white py-4 px-6 flex flex-col  gap-y-4 border border-[#F1F1F5] min-w-[280px] font-[Roboto]" :class="dropdownClass">
                <slot></slot>
            </div>
        </div>
    </div>
</template>

<script setup>

import {ref} from 'vue'

const props = defineProps({
    myClass: String,
    dropdownClass: String
})

const isOpen = ref(false);

function handleOnClick(e) {
    isOpen.value = !isOpen.value;
}

</script>
