<template>
    <CenteredLayout>
        <div class="flex flex-col items-center justify-center h-dvh">
            <h1 class="text-6xl font-extrabold text-primary">401</h1>
            <p class="text-lg text-gray-500">Vocé não tem autorização para aceder a pagina que pretende ver</p>
            <img src="@/assets/images/illustrations/unauthorized.svg" alt="404 illustration" class="h-1/2 mt-8">
            <RouterLink v-if="!isAuthenticated" :to="{ name: 'Login' }"
                class="mt-4 px-4 py-2 bg-primary text-white rounded-lg">Entrar</RouterLink>
            <RouterLink v-if="isAuthenticated && getterMode == 'management'" :to="{ name: 'ManagementDashboard' }"
                class="mt-4 px-4 py-2 bg-primary text-white rounded-lg">Voltar para o Painel</RouterLink>
            <RouterLink v-if="isAuthenticated && getterMode == 'store'" :to="{ name: 'StoreDashboard' }"
                class="mt-4 px-4 py-2 bg-primary text-white rounded-lg">Voltar para o Painel</RouterLink>
        </div>
    </CenteredLayout>
</template>

<script setup lang="ts">
import CenteredLayout from '@/layouts/CenteredLayout.vue';

import { useAuthStore } from '@/stores/auth';

const { isAuthenticated, getterMode } = useAuthStore();
</script>
