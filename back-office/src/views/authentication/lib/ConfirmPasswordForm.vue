<template>
    <CoelhoAlert v-model="alert.show" variant="error" :message="alert.message" :type="alert.type"
        :title="alert.title" />
    <div class="flex flex-col gap-4">
        Para continuar, confirme sua palavra-passe.
        <CoelhoInput label="Palavra-passe" type="password" placeholder="Confirme sua palavra-passe" id="password"
            autocomplete="current-password" v-model="password" :error="passwordError" :leftIcon="LockClosedIcon" />
    </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';

import { validators } from '@/utils';

import { CoelhoAlert, CoelhoInput } from '@/components';
import { LockClosedIcon } from '@heroicons/vue/24/solid';

const props = defineProps<{
    showAlert?: boolean;
}>();
const emits = defineEmits(['submit']);

const password = ref('');
const passwordError = ref('');
const alert = ref<{ show: boolean, message: string, title: string, type: 'error' | 'success' | 'warning' | 'info' }>({
    show: false,
    message: '',
    title: '',
    type: 'error'
});

watch(
    () => props.showAlert,
    (newValue) => {
        if (newValue) {
            alert.value.show = true;
            alert.value.message = 'Palavra-passe inválida';
            alert.value.title = 'Erro';
            alert.value.type = 'error';
        }
    },
);

const submitConfirmPasswordForm = () => {
    const error = validators.user.validateConfirmPasswordData(password.value);

    if (Object.values(error).some((error) => error !== '')) {
        return;
    }

    emits('submit', password.value);
};

defineExpose({ submitConfirmPasswordForm });
</script>