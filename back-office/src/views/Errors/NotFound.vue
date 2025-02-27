<template>
    <DefaultLayout>
        <div class="flex h-screen flex-col items-center justify-center">
            <h1 class="text-5xl font-bold text-red-500">404</h1>
            <p class="text-lg text-gray-500">Oops! Page non trouvée.</p>
            <img src="@/assets/images/illustrations/error-not-found.svg" alt="404 illustration" class="w-1/2 mt-8">
            <RouterLink to="/" class="mt-4 px-4 py-2 bg-blue-500 text-white rounded-lg">Retour à l'accueil</RouterLink>
        </div>
    </DefaultLayout>
</template>

<script setup lang="ts">
import DefaultLayout from '@/layouts/DefaultLayout.vue';
</script>
