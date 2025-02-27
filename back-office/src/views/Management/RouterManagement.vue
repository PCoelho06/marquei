<template>
    <ManagementLayout>
        <RouterView :key="$route.fullPath" />
    </ManagementLayout>
</template>

<script setup lang="ts">
import ManagementLayout from '@/layouts/ManagementLayout.vue';
</script>