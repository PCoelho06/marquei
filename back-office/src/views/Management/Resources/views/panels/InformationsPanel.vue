<template>
    <CoelhoCard v-if="getterResource" title="Informações gerais">
        <CoelhoData label="Tipo" :content="mappers.mapResourceTypeValueToLabel(getterResource.type)" />
        <CoelhoData label="Nome" :content="getterResource.name" />
        <CoelhoData label="Salão" :content="getterResource.salon.name" />
        <template #footer>
            <CoelhoButton :icon="PencilIcon" @click="showModal = true">
                Editar
            </CoelhoButton>
        </template>
    </CoelhoCard>
    <CoelhoModal v-model="showModal" title="Editar um recurso" size="xl">
        <ResourceForm :resource="getterResource" ref="resourceFormRef" @submit="handleResourceSubmit" />
        <template #footer>
            <CoelhoButton :icon="XMarkIcon" @click="showModal = false" variant="secondary">
                Cancelar
            </CoelhoButton>
            <CoelhoButton :icon="PencilIcon" @click="resourceFormRef?.submitResourceForm()">
                Editar
            </CoelhoButton>
        </template>
    </CoelhoModal>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { storeToRefs } from 'pinia';

import { useResourcesStore } from '@/stores/resources';

import { mappers } from '@/utils';

import ResourceForm from '../../lib/ResourceForm.vue';

import { CoelhoCard, CoelhoButton, CoelhoData, CoelhoModal } from '@/components';
import { PencilIcon, XMarkIcon, } from '@heroicons/vue/24/solid';

import type { ComponentPublicInstance } from 'vue';
import type { ResourceCreatePayload, ResourceUpdatePayload } from '@/types/resources'

const resourcesStore = useResourcesStore();
const { getterResource } = storeToRefs(resourcesStore);

const showModal = ref(false);
const resourceFormRef = ref<ComponentPublicInstance & { submitResourceForm: () => void }>();

const handleResourceSubmit = async (resourceData: ResourceUpdatePayload) => {
    showModal.value = false;
    resourceData.salon = Number(resourceData.salon);

    await resourcesStore.updateResource(resourceData)
};
</script>