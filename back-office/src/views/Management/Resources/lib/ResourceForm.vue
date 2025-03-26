<template>
    <div v-if="hasAtLeastOneSalon && isReady" class="grid grid-cols-1 gap-8 m-6 sm:grid-cols-2">
        <CoelhoSelect v-model="localResource.type" :leftIcon="TagIcon" label="Tipo de recurso" component="select"
            :error="validationErrors.type" :options="resourceTypesOptions" :required="true" />
        <CoelhoSelect v-model="localResource.salon" :leftIcon="BuildingStorefrontIcon" label="Salão" component="select"
            :searchable="true" :error="validationErrors.salon" :options="salonOptions" :required="true" />
        <CoelhoInput v-model="localResource.name" label="Nome do recurso" placeholder="Entre o nome do recurso"
            :error="validationErrors.name" :leftIcon="ChatBubbleLeftEllipsisIcon" :required="true" />
    </div>
    <div v-else class="grid grid-cols-1 gap-4 my-8">
        <p>Não existem salões registados. Por favor, registe um salão antes de registar um recurso.</p>
        <CoelhoButton :icon="PlusCircleIcon" @click="router.push({ name: 'CreateSalon' })">Registar um salão
        </CoelhoButton>
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';

import { resourceTypesOptions } from '@/composables/constants';
import { mappers, validators } from '@/utils';

import { useSalonsStore } from '@/stores/salons';

import { CoelhoButton, CoelhoSelect, CoelhoInput } from '@/components';
import { ChatBubbleLeftEllipsisIcon, PlusCircleIcon, BuildingStorefrontIcon, TagIcon } from '@heroicons/vue/24/solid';

import type { SelectOption } from '@/types';
import type { Resource, ResourceCreatePayload, ResourceUpdatePayload } from '@/types/resources';

const props = defineProps<{ resource?: Resource }>();
const emits = defineEmits(['submit']);

const router = useRouter();

const salonsStore = useSalonsStore();

const isReady = ref(false);
const salonOptions = ref<SelectOption[]>([]);
const hasAtLeastOneSalon = ref(true);

const localResource = ref<ResourceCreatePayload | ResourceUpdatePayload>({
    ...(props.resource && 'id' in props.resource ? { id: props.resource.id } : {}),
    type: props.resource?.type || 'employee',
    salon: props.resource?.salon.id || 0,
    name: props.resource?.name || '',
});
const validationErrors = ref({
    type: '',
    salon: '',
    name: '',
})

const submitResourceForm = () => {
    validationErrors.value = validators.resources.validateResourceData(localResource.value);

    if (Object.values(validationErrors.value).some((error) => error !== '')) {
        return;
    }

    emits('submit', localResource.value);
};

defineExpose({ submitResourceForm });

onMounted(async () => {
    await salonsStore.searchSalons({ httpQuery: {} });
    if (salonsStore.getterSalonList && salonsStore.getterSalonList.length > 0) {
        salonOptions.value = mappers.mapSalonsToOptions(salonsStore.getterSalonList);
    } else {
        hasAtLeastOneSalon.value = false;
    }

    isReady.value = true;
})
</script>