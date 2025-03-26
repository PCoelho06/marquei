<template>
    <div class="grid grid-cols-1 gap-8 m-6 sm:grid-cols-2">
        <CoelhoInput :leftIcon="ChatBubbleLeftEllipsisIcon" type="text" label="Nome do serviço"
            :error="validationErrors.name" placeholder="Nome do serviço" v-model="localService.name" :required=true />
        <CoelhoInput :leftIcon="DocumentTextIcon" type="text" label="Descrição do serviço"
            :error="validationErrors.description" placeholder="Descrição do serviço"
            v-model="localService.description" />
        <CoelhoInput :leftIcon="ClockIcon" type="number" label="Duração do serviço" :error="validationErrors.duration"
            placeholder="Duração do serviço" v-model="localService.duration" suffix="min" :required=true />
        <CoelhoInput :leftIcon="BanknotesIcon" type="number" label="Preço do serviço" :error="validationErrors.price"
            placeholder="Preço do serviço" v-model="localService.price" suffix="€" :required=true />
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';

import { validators } from '@/utils'

import type { ServiceCreatePayload, ServiceUpdatePayload } from '@/types/services';

import { CoelhoInput } from '@/components';
import { BanknotesIcon, ChatBubbleLeftEllipsisIcon, ClockIcon, DocumentTextIcon } from '@heroicons/vue/24/solid';

const props = defineProps<{ service?: ServiceCreatePayload | ServiceUpdatePayload }>();
const emits = defineEmits(['submit']);

const localService = ref<ServiceCreatePayload | ServiceUpdatePayload>({
    ...(props.service && 'id' in props.service ? { id: props.service.id } : {}),
    name: props.service?.name || '',
    description: props.service?.description || '',
    duration: props.service?.duration || 0,
    price: (props.service?.price || 0) / 100,
    salonId: props.service?.salonId || 0,
});
const validationErrors = ref({
    name: '',
    description: '',
    duration: '',
    price: '',
});

const submitServiceForm = () => {
    validationErrors.value = validators.services.validateServiceData(localService.value);

    if (Object.values(validationErrors.value).some((error) => error !== '')) {
        return;
    }

    emits('submit', localService.value);
};

defineExpose({ submitServiceForm });
</script>
