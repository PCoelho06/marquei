<template>
    <ManagementLayout>
        <div v-if="isReady" class="container mx-auto">
            <SearchResource :loading=false @submit="fetchResourcesList" />
            <CoelhoDataTable v-if="formattedResourcesList?.length" :items="formattedResourcesList"
                :columns="columnsResources" :totalElements="getterResourceSettings?.totalElements"
                :first="getterResourceSettings?.first" :last="getterResourceSettings?.last"
                :totalPages="getterResourceSettings?.totalPages"
                @update:limit="query = { ...updateLimit(query, $event, fetchResourcesList) }"
                @update:sort="query = { ...updateSort(query, $event, libQuerySort, fetchResourcesList) }"
                @update:page="query = { ...updatePage(query, $event, getterResourceSettings?.totalPages ? getterResourceSettings.totalPages : 1, fetchResourcesList) }">
                <template #actions>
                    <CoelhoButton variant="primary" :icon="PlusCircleIcon"
                        @click="router.push({ name: 'AddRessource' })">
                        Adicionar
                    </CoelhoButton>
                </template>

                <template #rowActions="{ item }">
                    <div class="flex space-x-2">
                        <CoelhoButton variant="primary" size="sm" :icon="PencilIcon"
                            @click="router.push({ name: 'EditResource', params: { id: item.id } })" />
                        <CoelhoButton variant="danger" size="sm" :icon="TrashIcon"
                            @click="openModal('delete:resource', item.id)" />
                        <CoelhoModal v-model="isModalOpen" :title="modal?.title">
                            <p>{{ modal?.content }}</p>
                            <template #footer>
                                <CoelhoButton @click="isModalOpen = false" variant="secondary">
                                    {{ modal?.dismiss }}
                                </CoelhoButton>
                                <CoelhoButton @click="modal?.action" variant="danger">
                                    {{ modal?.validate }}
                                </CoelhoButton>
                            </template>
                        </CoelhoModal>
                    </div>
                </template>
            </CoelhoDataTable>
            <div v-else class="grid grid-cols-1 gap-4 my-8">
                <DefaultCard>
                    <template #default>
                        <div class="flex flex-col justify-center items-center gap-4">
                            <p>Nenhum recurso disponivel</p>
                            <CoelhoButton variant="primary" @click="router.push({ name: 'AddRessource' })">
                                Adicionar
                            </CoelhoButton>
                        </div>
                    </template>
                </DefaultCard>
            </div>
        </div>
    </ManagementLayout>
</template>

<script setup lang="ts">
import { onMounted, ref, computed, watch } from 'vue'
import { useRouter, onBeforeRouteLeave } from 'vue-router'
import { storeToRefs } from 'pinia'

import { mappers } from '@/utils'
import { engineQueries } from '@/composables/engineQueries'

import { useResourcesStore } from '@/stores/resources'

import { columnsResources } from '@/views/commons/composables/columnsResources'

import DefaultCard from '@/components/Cards/DefaultCard.vue'
import ManagementLayout from '@/layouts/ManagementLayout.vue'
import { CoelhoDataTable, CoelhoButton, CoelhoIcon, CoelhoModal } from '@/components'
import { PencilIcon, TrashIcon, PlusCircleIcon } from '@heroicons/vue/24/solid'
import SearchResource from './$filters/SearchResource.vue'
import type { ResourceQuery } from '@/types'

const { formatForRouter, updateLimit, updatePage, updateSort } = engineQueries()
const router = useRouter()

const isModalOpen = ref(false)
const modal = ref<{ title: string, content: string, dismiss: string, validate: string, action: () => void }>()
const isReady = ref<boolean>(false)
const query = ref<ResourceQuery>({
    page: 1,
    limit: 10,
    sort: [],
});

const libQuerySort = ['name', 'salon', 'type']

const resourcesStore = useResourcesStore()
const { getterResourceList, getterQuery, getterResourceSettings } = storeToRefs(resourcesStore)

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
    console.log('delete resource', id)
    await resourcesStore.deleteResource({ id, ...query.value })
    fetchResourcesList()
    isModalOpen.value = false
}

const openModal = (type: string, id: number | string) => {
    switch (type) {
        case 'delete:resource':
            modal.value = {
                title: 'Eliminar recurso',
                content: 'Tem a certeza que deseja eliminar este recurso?',
                dismiss: 'Cancelar',
                validate: 'Eliminar',
                action: async () => await deleteResource(Number(id)),
            }
            isModalOpen.value = true
            break
    }
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
    isReady.value = true

})

onBeforeRouteLeave((leaveGuard) => {
    if (!leaveGuard.path.includes("/employeurs")) resourcesStore.setQuery(undefined);
});
</script>