<template>
    <ManagementLayout>
        <div class="mx-auto">
            <ResourcesFilters :loading=false @submit="fetchResourcesList" />
            <CoelhoDataTable v-if="formattedResourcesList?.length" :items="formattedResourcesList"
                :columns="columnsResources" :totalElements="getterResourceSettings?.totalElements"
                :first="getterResourceSettings?.first" :last="getterResourceSettings?.last"
                :totalPages="getterResourceSettings?.totalPages"
                @update:limit="query = { ...updateLimit(query, $event, fetchResourcesList) }"
                @update:sort="query = { ...updateSort(query, $event, libQuerySort, fetchResourcesList) }"
                @update:page="query = { ...updatePage(query, $event, getterResourceSettings?.totalPages ? getterResourceSettings.totalPages : 1, fetchResourcesList) }">
                <template #actions>
                    <CoelhoButton variant="primary" :icon="PlusCircleIcon" @click="openModal('create:resource')">
                        Adicionar um recurso
                    </CoelhoButton>
                </template>

                <template #rowActions="{ item }">
                    <div class="flex space-x-2">
                        <CoelhoButton variant="primary" size="sm" :icon="PencilIcon"
                            @click="openModal('edit:resource', item.id)" />
                        <CoelhoButton variant="danger" size="sm" :icon="TrashIcon"
                            @click="openModal('delete:resource', item.id)" />
                    </div>
                </template>
            </CoelhoDataTable>
            <div v-else class="grid grid-cols-1 gap-4 my-8">
                <DefaultCard>
                    <template #default>
                        <div class="flex flex-col justify-center items-center gap-4">
                            <p>Nenhum recurso disponivel</p>
                            <CoelhoButton :icon="PlusCircleIcon" variant="primary"
                                @click="openModal('create:resource')">
                                Adicionar um recurso
                            </CoelhoButton>
                        </div>
                    </template>
                </DefaultCard>
            </div>
        </div>
        <CoelhoModal v-model="showModal" :title="modal?.title" size="xl">
            <ResourceForm v-if="modal?.content === 'ResourceForm'" :resource="modal.props" ref="resourceFormRef"
                @submit="handleResourceSubmit" />
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
    </ManagementLayout>
</template>

<script setup lang="ts">
import { onMounted, ref, computed } from 'vue'
import { useRouter, onBeforeRouteLeave } from 'vue-router'
import { storeToRefs } from 'pinia'

import { mappers } from '@/utils'
import { engineQueries } from '@/composables/engineQueries'

import { useResourcesStore } from '@/stores/resources'

import { columnsResources } from '@/views/commons/composables/columnsResources'

import DefaultCard from '@/components/Cards/DefaultCard.vue'
import ManagementLayout from '@/layouts/ManagementLayout.vue'
import ResourceForm from './lib/ResourceForm.vue'
import { CoelhoDataTable, CoelhoButton, CoelhoModal, CoelhoText } from '@/components'
import { PencilIcon, TrashIcon, PlusCircleIcon, XMarkIcon } from '@heroicons/vue/24/solid'
import ResourcesFilters from './$filters/ResourcesFilters.vue'
import type { Resource, ResourceCreatePayload, ResourceQuery, ResourceUpdatePayload } from '@/types/resources'
import type { ModalContent } from '@/types'
import type { ComponentPublicInstance } from 'vue';

interface ManageResourceModalContent extends ModalContent {
    props?: Resource;
}

const { formatForRouter, updateLimit, updatePage, updateSort } = engineQueries()
const router = useRouter()

const resourceFormRef = ref<ComponentPublicInstance & { submitResourceForm: () => void }>();
const showModal = ref(false)
const modal = ref<ManageResourceModalContent>()
const query = ref<ResourceQuery>({
    page: 1,
    limit: 10,
    sort: [],
});

const libQuerySort = ['name', 'salon', 'type']

const resourcesStore = useResourcesStore()
const { getterResourceList, getterQuery, getterResourceSettings } = storeToRefs(resourcesStore)

const validateModalAction = () => {
    if (modal.value?.content === 'ResourceForm') {
        resourceFormRef.value?.submitResourceForm();
    } else if (modal.value?.action) {
        modal.value.action();
    }
}

const handleResourceSubmit = (resourceData: ResourceCreatePayload | ResourceUpdatePayload) => {
    showModal.value = false;
    resourceData.salon = Number(resourceData.salon);

    if ('id' in resourceData && resourceData.id) {
        resourcesStore.updateResource(resourceData).then(() => {
            fetchResourcesList();
        });
    } else {
        resourcesStore.createResource(resourceData).then(() => {
            fetchResourcesList();
        });
    }
};

const formattedResourcesList = computed(() => {
    return getterResourceList.value?.map((resource) => {
        return {
            id: resource.id,
            type: mappers.mapResourceTypeValueToLabel(resource.type),
            name: resource.name,
            salon: resource.salon.name,
        }
    })
})

const deleteResource = async (id: number) => {
    await resourcesStore.deleteResource({ id, ...query.value })
    fetchResourcesList()
    showModal.value = false
}

const openModal = (type: string, data?: any) => {
    switch (type) {
        case 'create:resource':
            modal.value = {
                title: 'Adicionar um recurso',
                content: 'ResourceForm',
                dismiss: 'Cancelar',
                validate: 'Adicionar',
                validateIcon: PlusCircleIcon,
                validateVariant: 'primary',
            }
            break;
        case 'edit:resource':
            modal.value = {
                title: 'Editar um recurso',
                content: 'ResourceForm',
                dismiss: 'Cancelar',
                validate: 'Editar',
                validateIcon: PencilIcon,
                validateVariant: 'primary',
            }
            modal.value.props = getterResourceList.value?.find((resource) => resource.id === data);
            break;
        case 'delete:resource':
            modal.value = {
                title: 'Eliminar recurso',
                content: 'Tem a certeza que deseja eliminar este recurso?',
                dismiss: 'Cancelar',
                validate: 'Eliminar',
                validateIcon: TrashIcon,
                validateVariant: 'danger',
                action: async () => await deleteResource(data),
            }
            break
    }
    showModal.value = true
}

const fetchResourcesList = async (args = {}) => {
    query.value = { ...query.value, ...args }

    await resourcesStore.searchResources({ httpQuery: query.value })

    resourcesStore.setQuery(query.value)

    router.push({ query: formatForRouter(query.value) });
}

onMounted(async () => {
    const httpQuery = getterQuery.value
        ? { ...getterQuery.value, ...router.currentRoute.value.query }
        : router.currentRoute.value.query;

    await fetchResourcesList(httpQuery)
})

onBeforeRouteLeave((leaveGuard) => {
    if (!leaveGuard.path.includes("/recursos")) resourcesStore.setQuery(undefined);
});
</script>