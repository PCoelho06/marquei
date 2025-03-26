<template>
    <form @submit.prevent="submitForm" class="flex flex-col gap-4">
        <CoelhoInput label="Email" type="email" placeholder="Entre seu email" v-model="user.email" id="email"
            autocomplete="email" :error="validationErrors.email" :leftIcon="EnvelopeIcon" />
        <CoelhoInput label="Palavra-passe" type="password" placeholder="Entre sua palavra-passe" id="password"
            autocomplete="new-password" v-model="user.password" :error="validationErrors.password"
            :leftIcon="LockClosedIcon" />
        <CoelhoInput label="Confirme a palavra-passe" type="password" placeholder="Confirme sua palavra-passe"
            autocomplete="new-password" id="confirmPassword" v-model="user.confirmPassword"
            :error="validationErrors.confirmPassword" :leftIcon="LockClosedIcon" />

        <div class="my-4">
            <CoelhoText size="sm" color="text-gray-500">
                Ao clicar em "Registar-me", você concorda com os nossos
                <CoelhoLink :to="router.resolve({ name: 'Terms' }).href" size="sm" class="text-primary">
                    Termos de Serviço
                </CoelhoLink>
                e
                <CoelhoLink :to="router.resolve({ name: 'Privacy' }).href" size="sm" class="text-primary">
                    Política de Privacidade
                </CoelhoLink>.
            </CoelhoText>
            <CoelhoButton type="submit" :icon="UserPlusIcon" size="lg" class="w-full mt-1">
                Registar-me
            </CoelhoButton>
        </div>

        <div class="mt-6 text-center">
            <CoelhoText size="sm">
                Já tem conta ?
                <CoelhoLink :to="router.resolve({ name: 'Login' }).href" size="sm" class="text-primary">Entrar
                </CoelhoLink>
            </CoelhoText>
        </div>
    </form>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'

import { useAuthStore } from '@/stores/auth'

import { validators } from '@/utils'

import { CoelhoButton, CoelhoInput, CoelhoLink, CoelhoText } from '@/components'
import { UserPlusIcon, EnvelopeIcon, LockClosedIcon } from '@heroicons/vue/24/solid'

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