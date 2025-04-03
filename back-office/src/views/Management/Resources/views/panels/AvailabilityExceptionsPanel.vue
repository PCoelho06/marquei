<template>
    <CoelhoCard v-if="getterResource" size="full" title="Proximas indisponibilidades">
        <CoelhoDataTable v-if="formattedResourceExceptionsList?.length" :items="formattedResourceExceptionsList"
            :columns="columnsResourceExceptions" :totalElements="getterResourceExceptionSettings?.totalElements"
            :first="getterResourceExceptionSettings?.first" :last="getterResourceExceptionSettings?.last"
            :totalPages="getterResourceExceptionSettings?.totalPages"
            @update:limit="query = { ...updateLimit(query, $event, fetchResourceExceptionsList) }"
            @update:sort="query = { ...updateSort(query, $event, libQuerySort, fetchResourceExceptionsList) }"
            @update:page="query = { ...updatePage(query, $event, getterResourceExceptionSettings?.totalPages ? getterResourceExceptionSettings.totalPages : 1, fetchResourceExceptionsList) }">
            <template #rowActions="{ item }">
                <div class="flex space-x-2">
                    <CoelhoButton variant="primary" size="sm" :icon="PencilIcon"
                        @click="openModal('edit:resource-exception', item.id)" />
                    <CoelhoButton variant="danger" size="sm" :icon="TrashIcon"
                        @click="openModal('delete:resource-exception', item.id)" />
                </div>
            </template>
        </CoelhoDataTable>
        <CoelhoText v-else :isParagraph="true" class="text-center">
            Não há indisponibilidades cadastradas para este recurso.
        </CoelhoText>
        <template #footer>
            <CoelhoButton :icon="PlusCircleIcon" variant="primary" @click="openModal('create:resource-exception')">
                Adicionar indisponibilidade
            </CoelhoButton>
        </template>
        <CoelhoModal v-model="showModal" :title="modal?.title" size="xl">
            <ResourceExceptionForm v-if="modal?.content === 'ResourceExceptionForm'" :resourceId="getterResource.id"
                :resourceException="modal.props" ref="resourceExceptionFormRef"
                @submit="handleResourceExceptionSubmit" />
            <CoelhoText v-else>{{ modal?.content }}</CoelhoText>
            <template #footer>
                <CoelhoButton :icon="XMarkIcon" @click="showModal = false" variant="secondary">
                    {{ modal?.dismiss }}
                </CoelhoButton>
                <CoelhoButton :icon="modal?.validateIcon" @click="validateModalAction"
                    :variant="modal?.validateVariant">
                    {{ modal?.validate }}
                </CoelhoButton>
            </template>
        </CoelhoModal>
    </CoelhoCard>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { storeToRefs } from 'pinia';

import { useResourcesStore } from '@/stores/resources';
import { useResourceExceptionsStore } from '@/stores/resources-exceptions';

import { formatters } from '@/utils';
import { engineQueries } from '@/composables/engineQueries';

import { columnsResourceExceptions } from '@/views/commons/composables/columns/columnsResourceExceptions'

import { CoelhoCard, CoelhoButton, CoelhoText, CoelhoModal, CoelhoDataTable } from '@/components';
import { PlusCircleIcon, TrashIcon, XMarkIcon, PencilIcon } from '@heroicons/vue/24/solid';

import ResourceExceptionForm from '@/views/management/resources/lib/ResourceExceptionForm.vue';

const router = useRouter();
const route = useRoute();

const resourcesStore = useResourcesStore();
const resourceExceptionsStore = useResourceExceptionsStore();
const { getterResource } = storeToRefs(resourcesStore);
const { getterResourceExceptionList, getterResourceExceptionSettings, getterQuery } = storeToRefs(resourceExceptionsStore);

const { formatForRouter, updateLimit, updatePage, updateSort } = engineQueries()

import type { ComponentPublicInstance } from 'vue';
import type { ResourceExceptionCreatePayload, ResourceException, ResourceExceptionUpdatePayload } from '@/types/resource-exceptions';
import type { BaseQuery, ModalContent } from '@/types';

interface ManageResourceExceptionModalContent extends ModalContent {
    props?: ResourceException;
}

const resourceExceptionFormRef = ref<ComponentPublicInstance & { submitResourceExceptionForm: () => void }>();
const showModal = ref(false);
const modal = ref<ManageResourceExceptionModalContent>()
const query = ref<BaseQuery>({
    page: route.query.page ? Number(route.query.page) : 1,
    limit: route.query.limit ? Number(route.query.limit) : 3,
    sort: route.query.sort ? [route.query.sort.toString()] : ['date,desc'],
});

const libQuerySort = ['date', 'startTime', 'endTime']

const validateModalAction = () => {
    if (modal.value?.content === 'ResourceExceptionForm') {
        resourceExceptionFormRef.value?.submitResourceExceptionForm();
    } else if (modal.value?.action) {
        modal.value.action();
    }
}

const formattedResourceExceptionsList = computed(() => {
    return getterResourceExceptionList.value?.map((resourceException) => {
        return {
            id: resourceException.id,
            date: formatters.formatDate(resourceException.date),
            startTime: formatters.formatTime(resourceException.startTime),
            endTime: formatters.formatTime(resourceException.endTime),
        }
    })
})

const handleResourceExceptionSubmit = async (resourceExceptionData: ResourceExceptionCreatePayload | ResourceExceptionUpdatePayload) => {
    console.log("🚀 ~ handleResourceExceptionSubmit ~ resourceExceptionData:", resourceExceptionData)
    showModal.value = false;

    if ('id' in resourceExceptionData && resourceExceptionData.id) {
        await resourceExceptionsStore.updateResourceException(resourceExceptionData)
    } else {
        await resourceExceptionsStore.createResourceException(resourceExceptionData)
    }

    await resourceExceptionsStore.listResourceExceptions({
        resourceId: Number(route.params.id),
        httpQuery: query.value
    });
};

const openModal = (type: string, data?: any) => {
    switch (type) {
        case 'create:resource-exception':
            modal.value = {
                title: 'Adicionar indisponibilidade para ' + getterResource.value?.name,
                content: 'ResourceExceptionForm',
                dismiss: 'Cancelar',
                validate: 'Adicionar a indisponibilidade',
                validateIcon: PlusCircleIcon,
                validateVariant: 'primary',
            }
            break;
        case 'edit:resource-exception':
            modal.value = {
                title: 'Editar indisponibilidade para ' + getterResource.value?.name,
                content: 'ResourceExceptionForm',
                dismiss: 'Cancelar',
                validate: 'Editar a indisponibilidade',
                validateIcon: PencilIcon,
                validateVariant: 'primary',
                props: getterResourceExceptionList.value?.find((resourceException) => resourceException.id === data)
            }
            break;
        case 'delete:resource-exception':
            modal.value = {
                title: 'Eliminar a indisponibilidade',
                content: 'Tem a certeza que deseja eliminar esta indisponibilidade?',
                dismiss: 'Cancelar',
                validate: 'Eliminar',
                validateIcon: TrashIcon,
                validateVariant: 'danger',
                action: async () => await deleteResourceException(data),
            }
            break
    }
    showModal.value = true
}

const deleteResourceException = async (id: number) => {
    if (!getterResource.value) {
        return;
    }
    await resourceExceptionsStore.deleteResourceException({ id, resourceId: getterResource.value?.id })
    fetchResourceExceptionsList()
    showModal.value = false
}

const fetchResourceExceptionsList = (args = {}) => {
    query.value = { ...query.value, ...args }

    resourceExceptionsStore.setQuery(query.value)

    router.replace({ query: formatForRouter(query.value) });
}
</script>