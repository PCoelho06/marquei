<template>
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

        <CoelhoButton type="submit" :icon="ArrowRightEndOnRectangleIcon" size="lg" class="w-full my-4">
            Entrar
        </CoelhoButton>
    </form>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { storeToRefs } from 'pinia'
import { useRouter } from 'vue-router'

import { validators } from '@/utils'

import { useAuthStore } from '@/stores/auth'

import AlertToast from '@/components/Alerts/AlertToast.vue';

import InputGroup from '@/components/Forms/InputGroup.vue'
import MailIcon from '@/components/Icons/MailIcon.vue';
import LockIcon from '@/components/Icons/LockIcon.vue';

import type { UserLoginForm } from '@/types/user'
import { CoelhoButton } from '@/components'
import { ArrowRightEndOnRectangleIcon } from '@heroicons/vue/24/solid'

const router = useRouter()

const authStore = useAuthStore();
const { getterHasSalons } = storeToRefs(authStore);

const user = ref<UserLoginForm>({
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

const validationErrors = ref({
    email: '',
    password: '',
})
</script>