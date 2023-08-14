<template>
    <div class="flex-1 relative">
        <textarea :class="myClass" :placeholder="placeholder" :name="name" v-if="type === 'textarea'">{{value}}</textarea>
        <input v-else-if="value" :value="value" :id="id" :type="actualType" :placeholder="placeholder"  :name="name" class="w-full border rounded-[15px] px-[15px] text-[14px] py-[11px] bg-[#F1F1F5] text-[#92929D]" v-bind:class="myClass">
        <input v-else :id="id" :type="actualType" :placeholder="placeholder"  :name="name" class="w-full border rounded-[15px] px-[15px] text-[14px] py-[11px] bg-[#F1F1F5] text-[#92929D]" v-bind:class="myClass">
        <div @click="handleInputTypeChange" class="absolute top-[calc(50%-12px)] cursor-pointer right-[15px]">
            <slot></slot>
        </div>
    </div>
</template>

<script setup>
    import {ref} from 'vue';

    const props = defineProps({
        type: String,
        placeholder: String,
        name: String,
        myClass: String,
        id: String,
        value: String
    })

    const actualType = ref(props.type);

    function handleInputTypeChange() {
        if (props.type === 'password') {
            if(actualType.value === 'text') {
                actualType.value = 'password'
            } else {
                actualType.value = 'text'
            }
        }
    }
</script>
