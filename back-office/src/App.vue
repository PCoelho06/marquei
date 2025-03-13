<template>
  <RouterView />
</template>

<script setup lang="ts">
import { onBeforeMount } from 'vue';
import { RouterView } from 'vue-router'

import { useAuthStore } from './stores/auth';

const authStore = useAuthStore();

onBeforeMount(async () => {
  await authStore.initialize();
});
</script>
