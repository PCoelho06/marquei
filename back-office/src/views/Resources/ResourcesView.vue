<template>
    <div class="container mx-auto">
        <h1 class="text-2xl font-semibold">Recursos</h1>
        <div class="grid grid-cols-2 gap-4 my-4">
            <DefaultCard cardTitle="Empregados">
                <template #default>
                    <p class="text-center">
                        <span class="text-5xl font-semibold block">0</span>
                        <span class="text-sm text-gray-500">empregados</span>
                    </p>
                </template>
            </DefaultCard>
            <DefaultCard cardTitle="Maquinas">
                <template #default>
                    <p class="text-center">
                        <span class="text-5xl font-semibold block">0</span>
                        <span class="text-sm text-gray-500">máquinas disponiveis</span>
                    </p>
                </template>
            </DefaultCard>
        </div>
        <DataTable v-model:items="products" :columns="productColumns" />
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { storeToRefs } from 'pinia'

import { useResourcesStore } from '@/stores/resources'

import type { Employee, Machine } from '@/types/resources'

import DefaultCard from '@/components/Cards/DefaultCard.vue'
import DataTable from '@/components/Tables/DefaultTable.vue'

const resourcesStore = useResourcesStore()
const { getterEmployees, getterMachines } = storeToRefs(resourcesStore)

const resources = ref<(Employee | Machine)[]>()

const products = ref([
    { id: 1, reference: 'PROD001', name: 'Laptop', price: 999, stock: 50 },
    { id: 2, reference: 'PROD002', name: 'Smartphone', price: 699, stock: 100 },
])

const productColumns = [
    { key: 'reference', label: 'Référence', editable: false },
    { key: 'name', label: 'Nom' },
    { key: 'price', label: 'Prix' },
    { key: 'stock', label: 'Stock' },
]

onMounted(async () => {
    await resourcesStore.getEmployees()
    if (getterEmployees.value && getterEmployees.value.length > 0) {
        resources.value = [...getterEmployees.value]
    }
    await resourcesStore.getMachines()
    if (getterMachines.value && getterMachines.value.length > 0) {
        resources.value?.push(...getterMachines.value)
    }
    console.log("🚀 ~ onMounted ~ resources.value:", resources.value)
})
</script>