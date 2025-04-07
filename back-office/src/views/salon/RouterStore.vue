<template>
    <StoreLayout>
        <RouterView :key="$route.fullPath" />
    </StoreLayout>
</template>

<script setup lang="ts">
import StoreLayout from '@/layouts/StoreLayout.vue';


</script>