<template>
    <DefaultLayout>
        <RouterView :key="$route.fullPath" />
    </DefaultLayout>
</template>

<script setup lang="ts">
import DefaultLayout from '@/layouts/DefaultLayout.vue'
</script>