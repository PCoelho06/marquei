<template>
    <div class="h-screen flex justify-center items-center">
        <AuthCard subtitle="Comece agora" title="Registar o meu salão na Marquei" class="w-8/10 m-auto">
            <form @submit.prevent="submitForm">
                <InputGroup label="Email" type="email" placeholder="Entre seu email" v-model="user.email" id="email"
                    autocomplete="email" :error="validationErrors.email">
                    <MailIcon class="fill-current" />
                </InputGroup>

                <InputGroup label="Palavra-passe" type="password" placeholder="Entre sua palavra-passe" id="password"
                    autocomplete="new-password" v-model="user.password" :error="validationErrors.password">
                    <LockIcon class="fill-current" />
                </InputGroup>

                <InputGroup label="Confirme a palavra-passe" type="password" placeholder="Confirme sua palavra-passe"
                    autocomplete="new-password" id="confirmPassword" v-model="user.confirmPassword"
                    :error="validationErrors.confirmPassword">
                    <LockIcon class="fill-current" />
                </InputGroup>

                <DefaultButton value="Registar-me" class="my-6" />

                <button
                    class="flex w-full items-center justify-center gap-3.5 rounded-lg border border-stroke bg-whitten p-4 font-medium hover:bg-opacity-80">
                    <GoogleIcon class="fill-current" />
                    Continue com Google
                </button>

                <div class="mt-6 text-center">
                    <p class="font-medium">
                        Já tem conta ?
                        <router-link to="/signin" class="text-primary">Entrar</router-link>
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

import { validateUserRegistrationData } from '@/utils/validators/user'

import AuthCard from '@/components/Cards/AuthCard.vue'
import InputGroup from '@/components/Forms/InputGroup.vue'
import MailIcon from '@/components/Icons/MailIcon.vue';
import LockIcon from '@/components/Icons/LockIcon.vue';
import DefaultButton from '@/components/Buttons/DefaultButton.vue';
import GoogleIcon from '@/components/Icons/GoogleIcon.vue';

import type { UserRegisterPayload } from '@/types/user'

const router = useRouter();

const userStore = useUserStore();

const user = ref<UserRegisterPayload>({
    email: '',
    password: '',
    confirmPassword: ''
})

const validationErrors = ref({
    email: '',
    password: '',
    confirmPassword: ''
})

const submitForm = () => {
    let errors = validateUserRegistrationData(user.value);

    validationErrors.value = {
        email: '',
        password: '',
        confirmPassword: ''
    }

    if (Object.keys(errors).length > 0) {
        for (let key in errors) {
            const typedKey = key as keyof typeof validationErrors.value;
            validationErrors.value[typedKey] = errors[typedKey] ?? '';
        }
        return;
    }

    userStore.actionRegister({ email: user.value.email, password: user.value.password, role: 'ROLE_OWNER' }).then(() => {
        console.log('User registered successfully');
        router.push({ name: 'createSalon' });
    }).catch((error) => {
        const typedKey = error.field as keyof typeof validationErrors.value;
        validationErrors.value[typedKey] = error.message;
    });
}
</script>