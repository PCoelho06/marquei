<template>
    <CoelhoCard v-if="getterResource" size="full" title="Horário de trabalho">
        <CoelhoData v-for="daySchedule in weekSchedule" :key="daySchedule.day"
            :label="mappers.mapNumberToDayOfWeekShort(Number(daySchedule.day))"
            :content="daySchedule.timeRanges || 'Indisponivel'" />
        <template #footer>
            <CoelhoButton :icon="PencilIcon"
                :to="router.resolve({ name: 'ManageResourceAvailabilities', params: { id: getterResource?.id } }).href">
                Editar
            </CoelhoButton>
        </template>
    </CoelhoCard>
</template>

<script setup lang="ts">
import { ref, onMounted, watch, watchEffect } from 'vue';
import { useRouter } from 'vue-router';
import { storeToRefs } from 'pinia';

import { useSalonsStore } from '@/stores/salons';
import { useResourcesStore } from '@/stores/resources';

import { mappers } from '@/utils';

import { CoelhoCard, CoelhoButton, CoelhoData } from '@/components';
import { PencilIcon } from '@heroicons/vue/24/solid';

interface DaySchedule {
    day: number
    timeRanges: string
}

const router = useRouter();

const salonsStore = useSalonsStore();
const resourcesStore = useResourcesStore();
const { getterSalon } = storeToRefs(salonsStore);
const { getterResource } = storeToRefs(resourcesStore);

const weekScheduleConstructor = () => {
    const weekSchedule: DaySchedule[] = []
    if (!getterSalon.value || !getterSalon.value.businessHours) {
        return [
            { day: 0, timeRanges: '' },
            { day: 1, timeRanges: '' },
            { day: 2, timeRanges: '' },
            { day: 3, timeRanges: '' },
            { day: 4, timeRanges: '' },
            { day: 5, timeRanges: '' },
            { day: 6, timeRanges: '' }
        ]
    }

    for (const businessHour of getterSalon.value.businessHours) {
        weekSchedule.push({
            day: Number(businessHour.dayOfWeek),
            timeRanges: ''
        })
    }
    return weekSchedule
}

const weekSchedule = ref<DaySchedule[]>(weekScheduleConstructor())

const formatScheduleTimeRanges = (day: number, start: string, end: string) => {
    const daySchedule = weekSchedule.value.find(s => s.day === day)
    if (daySchedule) {
        if (!daySchedule.timeRanges) {
            daySchedule.timeRanges = `${start} - ${end}`
        } else {
            daySchedule.timeRanges += `, ${start} - ${end}`
        }
    }
}

watchEffect(() => {
    if (getterResource.value && getterResource.value.resourceAvailabilities) {
        weekSchedule.value = weekScheduleConstructor()
        for (const availability of getterResource.value.resourceAvailabilities) {
            const start = availability.startTime
            const end = availability.endTime
            formatScheduleTimeRanges(Number(availability.dayOfWeek), start, end)
        }
    }
})
</script>