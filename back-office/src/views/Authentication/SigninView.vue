<template>
    <div class="h-screen flex justify-center items-center">
        <AuthCard subtitle="Comece agora" title="Aceder ao meu salão na Marquei" class="w-8/10 m-auto">
            <AlertToast v-if="alert.show" :message="alert.message" :type="alert.type" :title="alert.title" />
            <form @submit.prevent="submitForm">
                <InputGroup label="Email" type="email" placeholder="Entre seu email" v-model="user.email" id="email"
                    autocomplete="email" :error="validationErrors.email">
                    <MailIcon class="fill-current" />
                </InputGroup>

                <InputGroup label="Palavra-passe" type="password" placeholder="Entre sua palavra-passe" id="password"
                    autocomplete="password" v-model="user.password" :error="validationErrors.password">
                    <LockIcon class="fill-current" />
                </InputGroup>

                <PrimaryButton value="Entrar" />

                <button
                    class="flex w-full items-center justify-center gap-3.5 rounded-lg border border-stroke bg-whitten p-4 font-medium hover:bg-opacity-80">
                    <GoogleIcon class="fill-current" />
                    Continue com Google
                </button>

                <div class="mt-6 text-center">
                    <p class="font-medium">
                        Não tem conta ?
                        <router-link to="/signup" class="text-primary">Registe-se</router-link>
                    </p>
                </div>
            </form>
        </AuthCard>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'

import { useUserStore } from '@/stores/user'

import AuthCard from '@/components/Cards/AuthCard.vue'
import InputGroup from '@/components/Forms/InputGroup.vue'
import MailIcon from '@/components/Icons/MailIcon.vue';
import LockIcon from '@/components/Icons/LockIcon.vue';
import PrimaryButton from '@/components/Buttons/PrimaryButton.vue';
import GoogleIcon from '@/components/Icons/GoogleIcon.vue';

import type { UserAuthPayload } from '@/types/user'

import { validateUserLoginData } from '@/utils/validators.ts'
import AlertToast from '@/components/Alerts/AlertToast.vue';

const router = useRouter()

const userStore = useUserStore();

const user = ref<UserAuthPayload>({
    email: '',
    password: '',
})

const validationErrors = ref({
    email: '',
    password: '',
})

const alert = ref<{ show: boolean, message: string, title: string, type: 'error' | 'success' | 'warning' | 'info' }>({
    show: false,
    message: '',
    title: '',
    type: 'error'
});

const submitForm = () => {
    let errors = validateUserLoginData(user.value);

    alert.value.show = false;

    validationErrors.value = {
        email: '',
        password: '',
    }

    if (Object.keys(errors).length > 0) {
        for (let key in errors) {
            const typedKey = key as keyof typeof validationErrors.value;
            validationErrors.value[typedKey] = errors[typedKey] ?? '';
        }
        return;
    }


    userStore.actionLogin({ username: user.value.email, password: user.value.password }).then(() => {
        console.log('User loged successfully');
        router.push('/dashboard')
    }).catch((error) => {
        alert.value.show = true;
        alert.value.title = error.data.title;
        alert.value.message = error.data.message;
    });
}
</script>