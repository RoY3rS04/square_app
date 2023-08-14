<template>
    <SessionLayout title="Login to your account" :submit="handleSubmit">
        <template #inputs>
            <div>
                <Input placeholder="Your Email" name="email" type="Email"/>
                <span class="text-sm text-red-600" v-if="errors.email">{{errors.email}}</span>
            </div>
            <div>
                <Input placeholder="Your Password" name="password" type="password">
                    <font-awesome-icon icon="fa-regular fa-eye"></font-awesome-icon>
                </Input>
                <span class="text-sm text-red-600"  v-if="errors.password">{{errors.password}}</span>
            </div>
        </template>
        <div class="mt-[5px] flex flex-col gap-y-[10px]">
            <button class="px-[15px] text-white text-[12px] font-semibold rounded-[10px] py-[11px] bg-[#0062FF]" type="submit">Register</button>
            <span class="text-center text-[#92929D] text-[12px]">OR</span>
            <button class="border flex items-center justify-center gap-x-3 px-[15px] py-[11px] rounded-[10px] border-[#f1f1f5] text-[#696974] text-[12px] font-semibold" type="button">
                <GoogleIcon></GoogleIcon>
                Continue with Google
            </button>
        </div>
        <div class="text-[#0062FF] text-center text-[12px] mt-[69px]">
            <a href="#">Can't login?</a>
            ·
            <a @click="router.visit('register')" href="#">Sign up for new user</a>
        </div>
        <template #policies>
            <div class="text-white text-center text-[12px] mt-[25px]">
                <a href="#">Privacy policy</a>
                ·
                <a href="#">Terms of use</a>
            </div>
        </template>
    </SessionLayout>
</template>

<script setup>
    import SessionLayout from "../../components/SessionLayout.vue";
    import Input from "../../components/Input.vue";
    import GoogleIcon from "../../components/GoogleIcon.vue";
    import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
    import {router} from "@inertiajs/vue3";
    import axios from "axios";

    function handleSubmit(e) {
        const data = new FormData(e.target);

        router.post('http://127.0.0.1:8000/login', data);
    }

    const props = defineProps({
        errors: Object
    })

    console.log(props.errors);
</script>
