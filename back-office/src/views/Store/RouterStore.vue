<template>
    <StoreLayout>
        <RouterView :key="$route.fullPath" />
    </StoreLayout>
</template>

<script setup lang="ts">
import { onMounted } from 'vue';

import StoreLayout from '@/layouts/StoreLayout.vue';

onMounted(() => {
    console.log('Store Router Store');
});
</script>