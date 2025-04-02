<template>
    <ManagementLayout>
        <CoelhoLink @click="router.back()" class="flex items-center gap-2">
            <CoelhoIcon :icon="ArrowLeftIcon" />
            Voltar
        </CoelhoLink>
        <div class="mx-auto">
            <div class="grid grid-cols-2 gap-4 my-4">
                <div class="flex flex-col gap-4">
                    <InformationsPanel />
                    <AvailabilitiesPanel />
                    <AvailabilityExceptionsPanel />
                </div>
                <div class="h-full flex flex-col gap-4">
                    <BookingsStatisticsPanel class="h-1/2" />
                    <MoneyStatisticsPanel class="h-1/2" />
                </div>
            </div>
        </div>
    </ManagementLayout>
</template>

<script setup lang="ts">
import { onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';
import { storeToRefs } from 'pinia';

import { useResourcesStore } from '@/stores/resources';

import ManagementLayout from '@/layouts/ManagementLayout.vue';

import { CoelhoLink, CoelhoIcon } from '@/components';

import InformationsPanel from './views/panels/InformationsPanel.vue';
import { ArrowLeftIcon } from '@heroicons/vue/24/solid';
import AvailabilitiesPanel from './views/panels/AvailabilitiesPanel.vue';
import AvailabilityExceptionsPanel from './views/panels/AvailabilityExceptionsPanel.vue';
import BookingsStatisticsPanel from './views/panels/BookingsStatisticsPanel.vue';
import MoneyStatisticsPanel from './views/panels/MoneyStatisticsPanel.vue';

const route = useRoute();
const router = useRouter();

const resourcesStore = useResourcesStore();
const { getterResource } = storeToRefs(resourcesStore);

onMounted(async () => {
    await resourcesStore.getResource({ id: Number(route.params.id) });
});
</script>