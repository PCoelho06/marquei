<template>
    <CoelhoCard size="full" class="h-full overflow-y-scroll">
        <div class="flex justify-center items-center gap-4 p-4 overflow-y-auto">
            <CoelhoButton :transparent="true" @click="$emit('timeline:day:previous')">
                <CoelhoIcon :icon="ChevronLeftIcon" size="lg" color="secondary" />
            </CoelhoButton>
            <CoelhoHeading :level="2" :withMargin="false" size="sm">
                {{ formatters.formatDate(props.date) }}
            </CoelhoHeading>
            <CoelhoButton :transparent="true" @click="$emit('timeline:day:next')">
                <CoelhoIcon :icon="ChevronRightIcon" size="lg" color="secondary" />
            </CoelhoButton>
        </div>

        <div class="flex justify-end">
            <CoelhoButton :icon="PlusCircleIcon" @click="createBooking()" variant="primary">
                Adicionar agendamento
            </CoelhoButton>
        </div>

        <div class="flex p-4">
            <div class="w-20 border-x border-gray-200 bg-gray-50">
                <div class="h-16 border-y border-gray-200"></div>
                <div v-for="slot in timeSlots.slice(0, -1)" :key="slot.label"
                    class="relative h-8 flex items-center justify-end pr-2 text-sm text-gray-500 border-b border-gray-200">
                    <CoelhoText size="xs" color="secondary" :isParagraph="true"
                        class="absolute -top-2 left-1/2 -translate-x-1/2 bg-gray-50">
                        {{ slot.label }}
                    </CoelhoText>
                </div>
                <div :key="timeSlots.slice(-1)[0].label" class="relative">
                    <CoelhoText size="xs" color="secondary" :isParagraph="true"
                        class="absolute -top-2 left-1/2 -translate-x-1/2 bg-gray-50">
                        {{ timeSlots.slice(-1)[0].label }}
                    </CoelhoText>
                </div>
            </div>

            <div class="flex-grow grid" :style="{ gridTemplateColumns: `repeat(${props.resources.length}, 1fr)` }">
                <div v-for="resource in props.resources" :key="resource.id"
                    class="h-16 flex items-center justify-center border border-l-0 border-gray-200 bg-gray-50 font-medium">
                    {{ resource.name }}
                </div>

                <template v-for="resource in props.resources" :key="resource.id">
                    <div class="relative border-r border-gray-200"
                        :style="{ height: `${(timeSlots.length - 1) * 32}px` }">
                        <div v-for="(slot, index) in timeSlots.slice(0, -1)" :key="slot.label"
                            class="absolute w-full h-8 border-b border-gray-200" :style="{ top: `${index * 32}px` }"
                            :class="{
                                'bg-primary/20 cursor-not-allowed': slot.isPassed,
                                'cursor-pointer': !slot.isPassed,
                            }"
                            @click="slot.time < new Date() || createBooking(resource.id, formatters.formatDateTimeForm(slot.time))">
                        </div>

                        <div v-for="appointment in appointmentsOnResource[resource.id]" :key="appointment.id"
                            class="absolute left-0.5 right-0.5 rounded px-2 py-1 overflow-hidden text-sm cursor-pointer bg-primary text-white"
                            :style="getAppointmentStyle(appointment)" @click="$emit('appointment:show', appointment)">
                            <CoelhoText size="xs" color="light" weight="bold" class="mb-2" :isParagraph="true">
                                <CoelhoIcon v-if="appointment.client.totalVisits == 0" :icon="StarIcon" size="xs" />
                                {{ formatters.formatDatetime(appointment.startsAt) }} - {{
                                    formatters.formatDatetime(appointment.endsAt) }} : {{ appointment.client.name }}
                            </CoelhoText>
                            <CoelhoText size="xs" color="light" :isParagraph="true">
                                {{ appointment.service.name }}
                            </CoelhoText>
                        </div>
                    </div>
                </template>
            </div>
        </div>
    </CoelhoCard>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';

import { getTimeSlots, getAppointmentTopAndHeight } from './composables/datetime';

import { CoelhoButton, CoelhoCard, CoelhoHeading, CoelhoIcon, CoelhoText } from '..';
import { ChevronLeftIcon, ChevronRightIcon, StarIcon, PlusCircleIcon } from '@heroicons/vue/24/solid';

import type { TimeSlot } from '../types/timeline';
import { formatters } from '@/utils';
import type { Appointment, AppointmentCreatePayload } from '@/types/appointments';
import type { Resource } from '@/types/resources';

interface Props {
    resources: Resource[];
    appointments: Appointment[];
    date?: Date;
    startTime?: string;
    endTime?: string;
}

const props = withDefaults(defineProps<Props>(), {
    startTime: '08:00',
    endTime: '18:00',
    date: () => new Date(),
});

const emits = defineEmits<{
    (e: 'timeline:day:previous'): void;
    (e: 'timeline:day:next'): void;
    (e: 'appointment:show', appointment: Appointment): void;
    (e: 'appointment:create', appointment: AppointmentCreatePayload): void;
}>();

const timeSlotHeight = ref<number>(32);
const timeStep = ref<number>(30);

const timeSlots = computed<TimeSlot[]>(() => getTimeSlots(props.date, props.startTime, props.endTime, timeStep.value));

const appointmentsOnResource = computed(() => {
    const appointmentsByResource: Record<string, Appointment[]> = {};

    props.resources.forEach(resource => {
        appointmentsByResource[resource.id] = props.appointments.filter(appointment =>
            appointment.resource === resource
        );
    });

    return appointmentsByResource;
});

const getAppointmentStyle = (appointment: Appointment) => {
    const { top, height } = getAppointmentTopAndHeight(props.startTime, appointment, timeSlotHeight.value, timeStep.value);

    return {
        top: `calc(${top}px - 1px)`,
        height: `calc(${height}px - 1px)`,
        width: `calc(100% - 4px)`,
    };
};

const createBooking = (resourceId?: number, date?: string) => {
    const appointment: AppointmentCreatePayload = {
        resourceId: resourceId || 0,
        clientId: 0,
        serviceId: 0,
        startsAt: date || getNextTimeSlot(),
        endsAt: '',
    };

    emits('appointment:create', appointment);
};

const getNextTimeSlot = () => {
    const currentDate = new Date();

    const nextSlotMinutes = currentDate.getMinutes() % timeStep.value === 0
        ? currentDate.getMinutes()
        : currentDate.getMinutes() + (timeStep.value - (currentDate.getMinutes() % timeStep.value));

    return formatters.formatDateTimeForm(new Date(currentDate.getFullYear(), currentDate.getMonth(), currentDate.getDate(), currentDate.getHours(), nextSlotMinutes));
}
</script>