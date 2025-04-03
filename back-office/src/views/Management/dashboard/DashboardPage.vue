<template>
    <ManagementLayout>
        <div class="flex flex-col w-full h-full">
            <h1 class="text-2xl font-semibold text-gray-800">Dashboard</h1>
            <CoelhoText>Olà {{ getterUser?.email }} ! Bem-vindo a Marquei Pro</CoelhoText>
        </div>
    </ManagementLayout>
</template>

<script setup lang="ts">
import { onMounted } from 'vue';
import { storeToRefs } from 'pinia';

import { useAuthStore } from '@/stores/auth';

import ManagementLayout from '@/layouts/ManagementLayout.vue';
import { CoelhoText } from '@/components';

const authStore = useAuthStore();
const { getterUser } = storeToRefs(authStore);

onMounted(async () => {
    if (!authStore.getterUser) {
        await authStore.fetchUser();
    }
});
</script>