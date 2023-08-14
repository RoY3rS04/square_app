<template>
    <SessionLayout title="Sign Up to your account" :submit="handleSubmit">
        <template #inputs>
            <div>
                <Input type="email" name="email" placeholder="Your Email"/>
                <span class="text-sm text-red-600" v-if="errors.email">{{errors.email}}</span>
            </div>
            <div>
                <Input type="text" name="name" placeholder="Your Name"/>
                <span class="text-sm text-red-600" v-if="errors.name">{{errors.name}}</span>
            </div>
            <div>
                <Input type="password" name="password" placeholder="Create Password">
                    <font-awesome-icon icon="fa-regular fa-eye"></font-awesome-icon>
                </Input>
                <span class="text-sm text-red-600" v-if="errors.password">{{errors.password}}</span>
            </div>
        </template>
            <p class="text-[12px] text-[#92929D] mt-[29px] text-center">
                By signing up, you confirm that you’ve read
                and accepted our <a class="text-[#0062FF] font-semibold" href="#">User Notice</a> and <a href="#" class="text-[#0062FF] font-semibold">Privacy Policy</a>.
            </p>
            <div class="mt-[5px] flex flex-col gap-y-[10px]">
                <button class="px-[15px] text-white text-[12px] font-semibold rounded-[10px] py-[11px] bg-[#0062FF]" type="submit">Register</button>
                <span class="text-center text-[#92929D] text-[12px]">OR</span>
                <button class="border flex items-center justify-center gap-x-3 px-[15px] py-[11px] rounded-[10px] border-[#f1f1f5] text-[#696974] text-[12px] font-semibold" type="button">
                    <GoogleIcon></GoogleIcon>
                    Continue with Google
                </button>
            </div>
            <a class="text-[#0062FF] font-semibold text-[12px] text-center" @click="router.visit('login')" href="#">Already have an Square account? Log in</a>
    </SessionLayout>
</template>

<script setup>
    import SessionLayout from "../../components/SessionLayout.vue";
    import GoogleIcon from "../../components/GoogleIcon.vue";
    import Input from "../../components/Input.vue";
    import {FontAwesomeIcon} from "@fortawesome/vue-fontawesome";
    import {router} from "@inertiajs/vue3";

    const props = defineProps({
        errors: Object
    })

    function handleSubmit(e) {
        const data = new FormData(e.target);

        router.post('http://127.0.0.1:8000/register', data);
    }
</script>
