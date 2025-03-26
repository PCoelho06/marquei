<template>
    <CoelhoCard title="Horários de funcionamento">
        <CoelhoData v-for="daySchedule in weekSchedule" :key="daySchedule.day"
            :label="mappers.mapNumberToDayOfWeekShort(Number(daySchedule.day))"
            :content="daySchedule.timeRanges || 'Encerrado'" />
        <template #footer>
            <CoelhoButton :icon="getterSalon?.businessHours?.length ? PencilIcon : PlusCircleIcon"
                :to="router.resolve({ name: 'HandleBusinessHours', params: { id: getterSalon?.id } }).href">
                {{ getterSalon?.businessHours?.length ? 'Editar' : 'Adicionar horários' }}
            </CoelhoButton>
        </template>
    </CoelhoCard>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { useRouter } from 'vue-router';
import { storeToRefs } from 'pinia';

import { mappers } from '@/utils';

import { useSalonsStore } from '@/stores/salons';

import { CoelhoCard, CoelhoButton, CoelhoData } from '@/components';
import { PencilIcon, PlusCircleIcon } from '@heroicons/vue/24/solid';

interface DaySchedule {
    day: number
    timeRanges: string
}

const router = useRouter();

const salonsStore = useSalonsStore();

const { getterSalon } = storeToRefs(salonsStore);

const weekSchedule = ref<DaySchedule[]>([
    { day: 1, timeRanges: '' },
    { day: 2, timeRanges: '' },
    { day: 3, timeRanges: '' },
    { day: 4, timeRanges: '' },
    { day: 5, timeRanges: '' },
    { day: 6, timeRanges: '' },
    { day: 0, timeRanges: '' },
])

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

onMounted(() => {
    getterSalon.value?.businessHours?.forEach((businessHour) => {
        formatScheduleTimeRanges(Number(businessHour.dayOfWeek), businessHour.startTime, businessHour.endTime)
    });
});
</script>