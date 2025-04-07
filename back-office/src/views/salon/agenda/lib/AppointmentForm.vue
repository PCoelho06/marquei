<template>
    <div v-if="isReady" class="grid grid-cols-1 gap-8 m-6 sm:grid-cols-2">
        <CoelhoDateTimePicker :initialValue="localAppointment.startsAt" />
        <div class="flex flex-col col-start-1 sm:col-span-2">
            <CoelhoCheckbox v-model="isNewClient" label="Novo Cliente" />
            <div v-if="isNewClient" class="grid grid-cols-2 gap-8">
                <CoelhoInput v-model="clientName" label="Nome do cliente" :leftIcon="UserIcon" :required="true" />
                <CoelhoInput v-model="clientPhone" label="Contacto do cliente" :leftIcon="PhoneIcon" :required="true" />
            </div>
            <div v-else class="grid grid-cols-2 gap-8">
                <CoelhoSelect v-model="localAppointment.clientId" :leftIcon="UserIcon" label="Cliente"
                    :searchable="true" :error="validationErrors.clientId" :options="clientOptions" :required="true" />
                <CoelhoInput v-model="clientPhone" label="Contacto do cliente" :leftIcon="PhoneIcon" :disabled="true" />
            </div>
        </div>
        <CoelhoSelect v-model="localAppointment.serviceId" :leftIcon="SparklesIcon" label="Serviço" :searchable="true"
            :error="validationErrors.serviceId" :options="serviceOptions" :required="true" />
        <CoelhoSelect v-model="localAppointment.resourceId" :leftIcon="RectangleGroupIcon" label="Recurso"
            :searchable="true" :error="validationErrors.resourceId" :options="resourceOptions" :required="true" />
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { useRoute } from 'vue-router';

import { formatters, mappers, validators } from '@/utils';

import { useServicesStore } from '@/stores/services';
import { useResourcesStore } from '@/stores/resources';

import { CoelhoSelect, CoelhoInput, CoelhoCheckbox, CoelhoDateTimePicker } from '@/components';
import { UserIcon, PhoneIcon, RectangleGroupIcon, SparklesIcon } from '@heroicons/vue/24/solid';

import type { SelectOption } from '@/types';
import type { AppointmentCreatePayload, AppointmentUpdatePayload } from '@/types/appointments';

const props = defineProps<{ appointment?: AppointmentCreatePayload | AppointmentUpdatePayload }>();
const emits = defineEmits(['submit']);

const route = useRoute();

const servicesStore = useServicesStore();
const resourcesStore = useResourcesStore();

const isReady = ref(false);
const isNewClient = ref(false);
const clientName = ref('');
const clientPhone = ref('');
const clientOptions = ref<SelectOption[]>([]);
const serviceOptions = ref<SelectOption[]>([]);
const resourceOptions = ref<SelectOption[]>([]);

const localAppointment = ref<AppointmentCreatePayload | AppointmentUpdatePayload>({
    ...(props.appointment && 'id' in props.appointment ? { id: props.appointment.id } : {}),
    resourceId: props.appointment?.resourceId || 0,
    clientId: props.appointment?.clientId || 0,
    serviceId: props.appointment?.serviceId || 0,
    startsAt: props.appointment?.startsAt || '',
    endsAt: props.appointment?.endsAt || '',
});
const validationErrors = ref({
    resourceId: '',
    clientId: '',
    serviceId: '',
    startsAt: '',
    endsAt: '',
})

const submitAppointmentForm = () => {
    validationErrors.value = validators.appointments.validateAppointmentsData(localAppointment.value);

    if (Object.values(validationErrors.value).some((error) => error !== '')) {
        return;
    }

    emits('submit', localAppointment.value);
};

defineExpose({ submitAppointmentForm });

onMounted(async () => {
    // Fetch clients
    // await clientsStore.searchClients({ httpQuery: {salonId: route.params.salonId} });
    // clientOptions.value = mappers.mapClientsToOptions(salonsStore.getterClientList);

    // Fetch services
    await servicesStore.searchServices({ httpQuery: { salonId: route.params.salonId } });

    if (!servicesStore.getterServiceList) {
        return;
    }
    serviceOptions.value = mappers.mapServicesToOptions(servicesStore.getterServiceList);

    if (!resourcesStore.getterResourceList) {
        return;
    }
    resourceOptions.value = mappers.mapResourcesToOptions(resourcesStore.getterResourceList);


    isReady.value = true;
})
</script>