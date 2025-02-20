<template>
    <DefaultLayout>
        <div class="flex flex-col w-full h-full">
            <h1 class="text-2xl font-semibold text-gray-800">Painel</h1>
            <div class="flex flex-col w-full h-full p-4">
                <div class="flex flex-col w-full h-1/2 bg-white shadow rounded-lg p-4">
                    <h2 class="text-lg font-semibold text-gray-800">Salons</h2>
                    <p class="text-sm text-gray-600">Total salons: 0</p>
                </div>
                <div class="flex flex-col w-full h-1/2 bg-white shadow rounded-lg p-4 mt-4">
                    <h2 class="text-lg font-semibold text-gray-800">Stylists</h2>
                    <p class="text-sm text-gray-600">Total stylists: 0</p>
                </div>
            </div>
        </div>
    </DefaultLayout>
</template>

<script setup lang="ts">
import DefaultLayout from '@/layouts/DefaultLayout.vue'


</script>