<template>
    <CenteredLayout>
        <div class="flex flex-col items-center justify-center h-dvh">
            <h1 class="text-6xl font-extrabold text-primary">404</h1>
            <p class="text-lg text-gray-500">Oops! A pagina que pretende visitar não foi encontrada.</p>
            <img src="@/assets/images/illustrations/error-not-found.svg" alt="404 illustration" class="h-1/2 mt-8">
            <RouterLink to="/" class="mt-4 px-4 py-2 bg-primary text-white rounded-lg">Voltar ao iniciò</RouterLink>
        </div>
    </CenteredLayout>
</template>

<script setup lang="ts">
import CenteredLayout from '@/layouts/CenteredLayout.vue';
</script>
