<template>
    <div class="grid grid-cols-1 gap-8 m-6 sm:grid-cols-2">
        <CoelhoInput :leftIcon="ChatBubbleLeftEllipsisIcon" type="text" label="Nome do serviço"
            :error="validationErrors.name" placeholder="Nome do serviço" v-model="localService.name" :required=true />
        <CoelhoInput :leftIcon="DocumentTextIcon" type="text" label="Descrição do serviço"
            :error="validationErrors.description" placeholder="Descrição do serviço"
            v-model="localService.description" />
        <CoelhoSelect :leftIcon="RectangleGroupIcon" label="Recursos" placeholder="Recurso"
            v-model="localService.resourcesIds" :options="resourcesList" :required=true :multiple=true />
        <CoelhoInput :leftIcon="ClockIcon" type="number" label="Duração do serviço" :error="validationErrors.duration"
            placeholder="Duração do serviço" v-model="localService.duration" suffix="min" :required=true />
        <CoelhoInput :leftIcon="BanknotesIcon" type="number" label="Preço do serviço" :error="validationErrors.price"
            placeholder="Preço do serviço" v-model="localService.price" suffix="€" :required=true />
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';

import { useResourcesStore } from '@/stores/resources';

import { validators } from '@/utils'

import type { Service, ServiceCreatePayload, ServiceUpdatePayload } from '@/types/services';

import { CoelhoInput, CoelhoSelect } from '@/components';
import { BanknotesIcon, ChatBubbleLeftEllipsisIcon, ClockIcon, DocumentTextIcon, RectangleGroupIcon } from '@heroicons/vue/24/solid';
import type { SelectOption } from '@/types';

const resourcesStore = useResourcesStore();
const resourcesList = ref<SelectOption[]>(resourcesStore.getterResourceList?.map((resource) => ({
    label: resource.name,
    value: resource.id,
})) || []);

const props = defineProps<{ service?: Service }>();
const emits = defineEmits(['submit']);

const localService = ref<ServiceCreatePayload | ServiceUpdatePayload>({
    ...(props.service && 'id' in props.service ? { id: props.service.id } : {}),
    name: props.service?.name || '',
    description: props.service?.description || '',
    duration: props.service?.duration || 0,
    price: (props.service?.price || 0) / 100,
    salonId: props.service?.salonId || 0,
    resourcesIds: props.service?.resources.map((resource) => resource.id) || [],
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
