<template>
    <div class="flex flex-col">
        <CoelhoData label="Data" :content="formatters.formatDateFromString(appointment.startsAt)" />
        <CoelhoData label="Hora"
            :content="`${formatters.formatDatetime(appointment.startsAt)} - ${formatters.formatDatetime(appointment.endsAt)}`" />
        <CoelhoData label="Cliente" :content="appointment.client.name" />
        <CoelhoData label="Contacto do cliente" :content="formatters.formatPhone(appointment.client.phone)" />
        <CoelhoData :label="mappers.mapResourceTypeValueToLabel(appointment.resource.type)"
            :content="appointment.resource.name" />
        <CoelhoData label="Serviço" :content="appointment.service.name" />
        <CoelhoData label="Duração" :content="getAppointmentDuration(appointment)" />
    </div>
</template>

<script setup lang="ts">
import { formatters, mappers } from '@/utils';

import { CoelhoData } from '@/components';

import type { Appointment } from '@/types/appointments';
import { getAppointmentDuration } from '@/components/organisms/composables/datetime';

defineProps<{
    appointment: Appointment
}>();
</script>