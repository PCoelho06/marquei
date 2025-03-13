<template>
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

        <div class="mt-6 text-center">
            <p class="font-medium">
                Já tem conta ?
                <router-link :to="{ name: 'Login' }" class="text-primary">Entrar</router-link>
            </p>
        </div>
    </form>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'

import { useAuthStore } from '@/stores/auth'

import { validators } from '@/utils'

import InputGroup from '@/components/Forms/InputGroup.vue'
import MailIcon from '@/components/Icons/MailIcon.vue';
import LockIcon from '@/components/Icons/LockIcon.vue';
import DefaultButton from '@/components/Buttons/DefaultButton.vue';

import type { UserRegisterForm } from '@/types/user'

const router = useRouter();

const authStore = useAuthStore();

const user = ref<UserRegisterForm>({
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
    let errors = validators.user.validateUserRegistrationData(user.value);

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

    authStore.actionRegister({ email: user.value.email, password: user.value.password }).then(() => {
        router.push({ name: 'CreateSalon' });
    }).catch((error) => {
        const typedKey = error.field as keyof typeof validationErrors.value;
        validationErrors.value[typedKey] = error.message;
    });
}
</script>