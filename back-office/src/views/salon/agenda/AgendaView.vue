<template>
    <div class="p-4 pb-8 w-full">
        <CoelhoHeading size="sm" class="w-full text-center">{{ getterSalon?.name }}</CoelhoHeading>
        <CoelhoTimeline v-if="isReady" :startTime :endTime :timeStep :date="timelineDate"
            @timeline:day:previous="changeDay(-1)" @timeline:day:next="changeDay(1)"
            @appointment:show="openModal('show:appointment', $event)"
            @appointment:create="openModal('create:appointment', $event)" />
        <CoelhoModal v-model="showModal" :size="showCreationModal ? 'full' : 'xl'"
            :title="showCreationModal ? createAppointmentModal.title : showDetailsAppointmentModal.title">
            <div v-if="showDetailsModal" class="flex items-center">
                <AppointmentDetails :appointment="showDetailsAppointmentModal.appointment" />
            </div>
            <div v-if="showCreationModal" class="flex items-center">
                <AppointmentForm :appointment="createAppointmentModal.appointment" ref="appointmentFormRef"
                    @submit="addAppointment" />
            </div>
            <template #footer>
                <CoelhoButton :icon="XMarkIcon" @click="showModal = false" variant="secondary">
                    {{ showCreationModal ? createAppointmentModal.dismiss : showDetailsAppointmentModal.dismiss }}
                </CoelhoButton>
                <CoelhoButton
                    :icon="showCreationModal ? createAppointmentModal.validateIcon : showDetailsAppointmentModal.validateIcon"
                    @click="validateModalAction"
                    :variant="showCreationModal ? createAppointmentModal.validateVariant : showDetailsAppointmentModal.validateVariant">
                    {{ showCreationModal ? createAppointmentModal.validate : showDetailsAppointmentModal.validate }}
                </CoelhoButton>
            </template>
        </CoelhoModal>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, computed, useTemplateRef, watch } from 'vue';
import { useRoute } from 'vue-router';
import { storeToRefs } from 'pinia';

import { CoelhoTimeline, CoelhoHeading, CoelhoModal, CoelhoButton } from '@/components';
import { XMarkIcon, TrashIcon, PlusCircleIcon } from '@heroicons/vue/24/solid';
import AppointmentDetails from './views/AppointmentDetails.vue';
import AppointmentForm from './lib/AppointmentForm.vue';

import { useSalonsStore } from '@/stores/salons';
import { useResourcesStore } from '@/stores/resources';
import { useAppointmentsStore } from '@/stores/appointments';
import { useClientsStore } from '@/stores/clients';

import type { ModalContent } from '@/types';
import type { Appointment, AppointmentCreatePayload } from '@/types/appointments';
import type { ClientCreatePayload } from '@/types/clients';

interface ShowDetailsAppointmentModalContent extends ModalContent {
    appointment: Appointment;
}
interface CreateAppointmentModalContent extends ModalContent {
    appointment?: AppointmentCreatePayload;
}

const route = useRoute();

const salonStore = useSalonsStore();
const resourcesStore = useResourcesStore();
const appointmentsStore = useAppointmentsStore();
const clientsStore = useClientsStore();
const { getterSalon } = storeToRefs(salonStore);
const { getterResourceList } = storeToRefs(resourcesStore);
const { getterClient } = storeToRefs(clientsStore);

const today = new Date();
today.setHours(0, 0, 0, 0);
const timelineDate = ref<Date>(today);

const appointmentFormRef = useTemplateRef('appointmentFormRef');
const timeStep = ref<number>(30);
const isReady = ref<boolean>(false);
const startTime = ref<string>('08:00');
const endTime = ref<string>('18:00');
const showDetailsModal = ref<boolean>(false);
const showCreationModal = ref<boolean>(false);
const showModal = ref<boolean>(showDetailsModal.value || showCreationModal.value);
const showDetailsAppointmentModal = ref<ShowDetailsAppointmentModalContent>({
    title: 'Detalhes do agendamento',
    content: 'AppointmentDetails',
    dismiss: 'Fechar',
    validate: 'Eliminar',
    validateVariant: 'danger',
    validateIcon: TrashIcon,
    appointment: {} as Appointment,
});
const createAppointmentModal = ref<CreateAppointmentModalContent>({
    title: 'Adicionar agendamento',
    content: 'AppointmentForm',
    dismiss: 'Fechar',
    validate: 'Adicionar',
    validateVariant: 'primary',
    validateIcon: PlusCircleIcon,
});

const validateModalAction = () => {
    if (showCreationModal) {
        appointmentFormRef.value?.submitAppointmentForm()
    } else {
        console.log('delete appointment');
    }
}

const openModal = (type: string, data: any) => {
    switch (type) {
        case 'show:appointment':
            showDetailsAppointmentModal.value.appointment = data;
            showDetailsModal.value = true;
            break;
        case 'create:appointment':
            createAppointmentModal.value.appointment = data;
            showCreationModal.value = true;
            break;
        default:
            break;
    }
};

const initiateAppointments = async () => {
    if (!getterResourceList.value?.length) return;

    await appointmentsStore.searchAppointments({
        httpQuery: {
            salonId: Number(route.params.salonId),
            startsAt: timelineDate.value.toLocaleDateString('pt-PT', {
                year: 'numeric',
                month: '2-digit',
                day: '2-digit',
                hour: '2-digit',
                minute: '2-digit',
            }),
        }
    });
}

const setBusinessHours = (day: string) => {
    const businessHours = getterSalon.value?.businessHours?.find((businessHour) => businessHour.dayOfWeek == day);
    if (!businessHours) return null;

    startTime.value = businessHours?.startTime || '08:00';
    endTime.value = businessHours?.endTime || '18:00';
};

const initiateTimeline = (day: string) => {
    initiateAppointments()
    setBusinessHours(day);
};

onMounted(async () => {
    if (getterSalon.value === undefined) {
        await salonStore.getSalon({ id: Number(route.params.salonId) });
    }
    await resourcesStore.searchResources({ salonId: Number(route.params.salonId) });

    initiateTimeline(timelineDate.value.getDay().toString());

    isReady.value = true;
});

const changeDay = (days: number) => {
    timelineDate.value = new Date(timelineDate.value.getTime() + days * 86400000);
    initiateTimeline(timelineDate.value.getDay().toString());
};

watch(showModal, (newValue) => {
    if (!newValue) {
        showDetailsModal.value = false;
        showCreationModal.value = false;
    }
});

watch([showDetailsModal, showCreationModal], () => {
    showModal.value = showDetailsModal.value || showCreationModal.value;
});

const addAppointment = async (data: { appointment: AppointmentCreatePayload; client: ClientCreatePayload } | AppointmentCreatePayload) => {
    console.log("🚀 ~ addAppointment ~ data:", data)
    if ('client' in data) {
        // creation du client
        await clientsStore.createClient(data.client);
    }
    // creation de l'appointment
    const newAppointment = 'client' in data ? data.appointment : data;
    const clientId = 'client' in data ? getterClient.value?.id : data.clientId;
    if (clientId !== undefined) {
        await appointmentsStore.createAppointment({ ...newAppointment, clientId });
    } else {
        console.error('Client ID is undefined. Cannot create appointment.');
    }
};
</script>