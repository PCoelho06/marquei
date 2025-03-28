<template>
    <AlertToast v-if="alert.show" :message="alert.message" :type="alert.type" :title="alert.title" />
    <form @submit.prevent="submitForm" class="flex flex-col gap-4">
        <CoelhoInput label="Email" type="email" placeholder="Entre seu email" v-model="user.email" id="email"
            autocomplete="email" :error="validationErrors.email" :leftIcon="EnvelopeIcon" />
        <CoelhoInput label="Palavra-passe" type="password" placeholder="Entre sua palavra-passe" id="password"
            autocomplete="current-password" v-model="user.password" :error="validationErrors.password"
            :leftIcon="LockClosedIcon" />
        <div class="my-4">
            <CoelhoText size="sm" color="secondary">
                Ao clicar em "Entrar", você concorda com os nossos
                <CoelhoLink :to="router.resolve({ name: 'Terms' }).href" size="sm" class="text-primary">
                    Termos de Serviço
                </CoelhoLink>
                e
                <CoelhoLink :to="router.resolve({ name: 'Privacy' }).href" size="sm" class="text-primary">
                    Política de Privacidade
                </CoelhoLink>.
            </CoelhoText>
            <CoelhoButton type="submit" :icon="ArrowRightEndOnRectangleIcon" size="lg" class="w-full mt-1">
                Entrar
            </CoelhoButton>
        </div>
        <div class="mt-6 text-center">
            <CoelhoText size="sm">
                Não tem conta ?
                <CoelhoLink :to="router.resolve({ name: 'Registration' }).href" size="sm" class="text-primary">
                    Registe-se
                </CoelhoLink>
            </CoelhoText>
        </div>
    </form>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { storeToRefs } from 'pinia'
import { useRouter } from 'vue-router'

import { validators } from '@/utils'

import { useAuthStore } from '@/stores/auth'

import AlertToast from '@/components/Alerts/AlertToast.vue';

import type { UserLoginForm } from '@/types/user'
import { CoelhoButton, CoelhoInput, CoelhoText, CoelhoLink } from '@/components'
import { ArrowRightEndOnRectangleIcon, EnvelopeIcon, LockClosedIcon } from '@heroicons/vue/24/solid'

const router = useRouter()

const authStore = useAuthStore();
const { getterHasSalons } = storeToRefs(authStore);

const user = ref<UserLoginForm>({
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
    let errors = validators.user.validateUserLoginData(user.value);

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


    authStore.actionLogin({ username: user.value.email, password: user.value.password }).then(() => {
        // If the user has no salons, redirect to create salon page
        if (!getterHasSalons.value) {
            router.push({ name: 'CreateSalon' });
            return;
        }
        router.push({ name: 'ModeSelect' })
    }).catch((error) => {
        alert.value.show = true;
        alert.value.title = error.data ? error.data.title : 'Erro';
        alert.value.message = error.data ? error.data.message : error.message;
    });
}
</script>