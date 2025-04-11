<template>
    <SalonLayout>
        <RouterView :key="$route.fullPath" />
    </SalonLayout>
</template>

<script setup lang="ts">
import SalonLayout from '@/layouts/SalonLayout.vue';


</script>