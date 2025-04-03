<template>
    <ManagementLayout>
        <CoelhoLink @click="router.back()" class="flex items-center gap-2">
            <CoelhoIcon :icon="ArrowLeftIcon" />
            Voltar
        </CoelhoLink>
        <div class="mx-auto">
            <div class="grid grid-cols-2 gap-4 my-4">
                <InformationsPanel />
                <AvailabilitiesPanel />
                <AvailabilityExceptionsPanel class="col-span-2" />
                <BookingsStatisticsPanel />
                <MoneyStatisticsPanel />
            </div>
        </div>
    </ManagementLayout>
</template>

<script setup lang="ts">
import { onMounted } from 'vue';
import { useRouter, useRoute } from 'vue-router';

import { useResourcesStore } from '@/stores/resources';
import { useResourceExceptionsStore } from '@/stores/resources-exceptions';

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
const resourceExceptionsStore = useResourceExceptionsStore();

onMounted(async () => {
    await resourcesStore.getResource({ id: Number(route.params.id) });
    await resourceExceptionsStore.listResourceExceptions({
        resourceId: Number(route.params.id), httpQuery: {
            page: route.query.page ? route.query.page : 1,
            limit: route.query.limit ? route.query.limit : 3,
            sort: route.query.sort ? [route.query.sort] : ['date,desc'],
        }
    });
});
</script>