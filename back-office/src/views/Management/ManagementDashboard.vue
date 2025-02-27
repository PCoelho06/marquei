<template>
    <div class="flex flex-col w-full h-full">
        <h1 class="text-2xl font-semibold text-gray-800">Painel</h1>
        <div class="flex flex-col w-full h-full p-4">
            <div class="flex flex-col w-full h-1/2 bg-white shadow rounded-lg p-4">
                <h2 class="text-lg font-semibold text-gray-800">Salons</h2>
                <p class="text-sm text-gray-600">Total salons: {{ salonData.totalElements }}</p>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';

import { useSalonsStore } from '@/stores/salons';

const salonsStore = useSalonsStore();

const salonData = ref<{
    totalElements: number | undefined
}>({
    totalElements: 0
});

onMounted(async () => {
    await salonsStore.listSalons();
    salonData.value.totalElements = salonsStore.getterSalons?.length;
});
</script>