<template>
    <div class="bg-[#0062FF] flex items-center justify-center min-h-screen font-[Poppins]">
        <div>
            <div class="flex items-center justify-center text-white text-[30px] font-semibold gap-x-[15px]">
                <SquareLogo></SquareLogo>
                Square
            </div>
            <div class="rounded-[20px] mt-10 max-w-[360px] min-w-[360px] px-5 pt-[30px] pb-[17px] bg-white">
                <h1 class="text-[18px] font-semibold text-center">{{ title }}</h1>
                <form @submit.prevent="submit" class="flex flex-col gap-y-[15px] mt-[30px]">
                    <slot name="inputs"></slot>
                    <slot>

                    </slot>
                </form>
            </div>
            <div>
                <slot name="policies">

                </slot>
            </div>
        </div>
    </div>
</template>

<script setup>
    import SquareLogo from "./SquareLogo.vue";

    const props = defineProps({
        title: String,
        submit: Function
    })
</script>

<style scoped>

</style>
