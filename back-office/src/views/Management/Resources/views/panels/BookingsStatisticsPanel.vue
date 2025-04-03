<template>
    <CoelhoCard v-if="getterResource" size="full" title="Estatísticas de reservas">
        Test
    </CoelhoCard>
</template>

<script setup lang="ts">
import { storeToRefs } from 'pinia';

import { useResourcesStore } from '@/stores/resources';

import { CoelhoCard } from '@/components';

const resourcesStore = useResourcesStore();
const { getterResource } = storeToRefs(resourcesStore);
</script>