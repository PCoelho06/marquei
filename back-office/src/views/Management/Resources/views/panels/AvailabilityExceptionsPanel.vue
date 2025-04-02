<template>
    <CoelhoCard v-if="getterResource" title="Proximas indisponibilidades">
        <!-- <CoelhoData v-for="daySchedule in weekSchedule" :key="daySchedule.day"
            :label="mappers.mapNumberToDayOfWeekShort(Number(daySchedule.day))"
            :content="daySchedule.timeRanges || 'Indisponivel'" /> -->
        <template #footer>
            <CoelhoButton :icon="PlusCircleIcon">
                Adicionar uma indisponibilidade
            </CoelhoButton>
        </template>
    </CoelhoCard>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { storeToRefs } from 'pinia';

import { useResourcesStore } from '@/stores/resources';

import { mappers } from '@/utils';

import { CoelhoCard, CoelhoButton, CoelhoData } from '@/components';
import { PlusCircleIcon } from '@heroicons/vue/24/solid';

const resourcesStore = useResourcesStore();
const { getterResource } = storeToRefs(resourcesStore);
</script>