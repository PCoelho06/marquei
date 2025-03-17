<template>
    <ManagementLayout>
        <div class="container mx-auto">
            <h1 class="text-2xl font-semibold">Recursos</h1>
            <div class="grid grid-cols-2 gap-4 my-4">
                <DefaultCard cardTitle="Empregados">
                    <template #default>
                        <p class="text-center">
                            <span class="text-5xl font-semibold block">{{ getterEmployees ? getterEmployees.length : 0
                            }}</span>
                            <span class="text-sm text-gray-500">empregados</span>
                        </p>
                    </template>
                </DefaultCard>
                <DefaultCard cardTitle="Maquinas">
                    <template #default>
                        <p class="text-center">
                            <span class="text-5xl font-semibold block">{{ getterMachines ? getterMachines.length : 0
                            }}</span>
                            <span class="text-sm text-gray-500">máquinas disponiveis</span>
                        </p>
                    </template>
                </DefaultCard>
            </div>
            <CoelhoDataTable v-if="resources?.length" :items="resources" :columns="columnsResources">
                <template #actions>
                    <CoelhoButton variant="primary" @click="router.push({ name: 'AddRessource' })">
                        Adicionar
                    </CoelhoButton>
                </template>

                <template #rowActions="{ item }">
                    <div class="flex space-x-2">
                        <CoelhoButton variant="primary" size="sm"
                            @click="router.push({ name: 'EditResource', params: { id: item.id } })">
                            <CoelhoIcon :icon="PencilIcon" size="sm" />
                        </CoelhoButton>
                        <CoelhoButton variant="danger" size="sm" @click="openModal('delete:resource', item.id)">
                            <CoelhoIcon :icon="TrashIcon" size="sm" />
                        </CoelhoButton>
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
import { onMounted, ref } from 'vue'
import { useRouter } from 'vue-router'
import { storeToRefs } from 'pinia'

import { mappers } from '@/utils'

import { useResourcesStore } from '@/stores/resources'

import { columnsResources } from '@/views/$composable/columnsResources'

import DefaultCard from '@/components/Cards/DefaultCard.vue'
import ManagementLayout from '@/layouts/ManagementLayout.vue'
import { CoelhoDataTable, CoelhoButton, CoelhoIcon, CoelhoModal } from '@coelhoui'
import { PencilIcon, TrashIcon } from '@heroicons/vue/24/solid'

const router = useRouter()

const resources = ref<{ id: number, type: string, name: string, salon: string }[]>()
const isModalOpen = ref(false)
const modal = ref<{ title: string, content: string, dismiss: string, validate: string, action: () => void }>()

const resourcesStore = useResourcesStore()
const { getterEmployees, getterMachines, getterResources } = storeToRefs(resourcesStore)

const deleteResource = async (id: number) => {
    console.log('delete resource', id)
    await resourcesStore.deleteResource({ id })
    loadResources()
    isModalOpen.value = false
}

const openModal = (type: string, id: number) => {
    switch (type) {
        case 'delete:resource':
            modal.value = {
                title: 'Eliminar recurso',
                content: 'Tem a certeza que deseja eliminar este recurso?',
                dismiss: 'Cancelar',
                validate: 'Eliminar',
                action: async () => await deleteResource(id),
            }
            isModalOpen.value = true
            break
    }
}

const loadResources = async () => {
    await resourcesStore.getResources()
    resources.value = getterResources.value?.map((resource) => {
        return {
            id: resource.id,
            type: mappers.mapResourceTypeValueToLabel(resource.type),
            name: resource.name,
            salon: resource.salon.name,
        }
    })
}

onMounted(async () => {
    await loadResources()
})
</script>