<template>
    <div class="flex flex-col w-full h-full">
        <h1 class="text-2xl font-semibold text-gray-800">{{ getterSalon?.name }}</h1>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';

import { useSalonsStore } from '@/stores/salons';
import { storeToRefs } from 'pinia';

const salonsStore = useSalonsStore();
const { getterSalon } = storeToRefs(salonsStore);
</script>