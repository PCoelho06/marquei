<template>
    <div class="grid grid-cols-1 gap-8 m-6 sm:grid-cols-2">
        <CoelhoInput :icon="ChatBubbleLeftEllipsisIcon" type="text" size="sm" label="Nome do serviço"
            :error="validationErrors.name" placeholder="Nome do serviço" v-model="localService.name" :required=true />
        <CoelhoInput :icon="DocumentTextIcon" type="text" size="sm" label="Descrição do serviço"
            :error="validationErrors.description" placeholder="Descrição do serviço"
            v-model="localService.description" />
        <CoelhoInput :icon="ClockIcon" type="number" size="sm" label="Duração do serviço"
            :error="validationErrors.duration" placeholder="Duração do serviço" v-model="localService.duration"
            :required=true />
        <CoelhoInput :icon="BanknotesIcon" type="number" size="sm" label="Preço do serviço"
            :error="validationErrors.price" placeholder="Preço do serviço" v-model="localService.price"
            :required=true />
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
