<template>
    <div class="p-4 pb-8 w-full">
        <CoelhoHeading size="sm" class="w-full text-center">{{ getterSalon?.name }}</CoelhoHeading>
        <CoelhoTimeline v-if="isReady" :resources="resources" :appointments :startTime :endTime :timeStep
            :date="timelineDate" @timeline:day:previous="changeDay(-1)" @timeline:day:next="changeDay(1)"
            @appointment:show="openModal('show:appointment', $event)"
            @appointment:create="openModal('create:appointment', $event)" />
        <CoelhoModal v-model="showModal" size="xl"
            :title="showCreationModal ? createAppointmentModal.title : showDetailsAppointmentModal.title">
            <div v-if="showDetailsModal" class="flex items-center">
                <AppointmentDetails :appointment="showDetailsAppointmentModal.appointment" />
            </div>
            <div v-if="showCreationModal" class="flex items-center">
                <AppointmentForm :appointment="createAppointmentModal.appointment" ref="appointmentFormRef" />
            </div>
            <template #footer>
                <CoelhoButton :icon="XMarkIcon" @click="showModal = false" variant="secondary">
                    {{ showCreationModal ? createAppointmentModal.dismiss : showDetailsAppointmentModal.dismiss }}
                </CoelhoButton>
                <CoelhoButton
                    :icon="showCreationModal ? createAppointmentModal.validateIcon : showDetailsAppointmentModal.validateIcon"
                    @click="showCreationModal ? createAppointmentModal.action : showDetailsAppointmentModal.action"
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

import type { ModalContent } from '@/types';
import type { Appointment, AppointmentCreatePayload } from '@/types/appointments';
import type { Resource } from '@/types/resources';

interface ShowDetailsAppointmentModalContent extends ModalContent {
    appointment: Appointment;
}
interface CreateAppointmentModalContent extends ModalContent {
    appointment?: AppointmentCreatePayload;
}

const route = useRoute();

const salonStore = useSalonsStore();
const resourcesStore = useResourcesStore();
const { getterSalon } = storeToRefs(salonStore);
const { getterResourceList } = storeToRefs(resourcesStore);

const timelineDate = ref<Date>(new Date());

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
    action: () => {
        console.log('delete appointment');
    },
    validateIcon: TrashIcon,
    appointment: {} as Appointment,
});
const createAppointmentModal = ref<CreateAppointmentModalContent>({
    title: 'Adicionar agendamento',
    content: 'AppointmentForm',
    dismiss: 'Fechar',
    validate: 'Adicionar',
    validateVariant: 'primary',
    action: () => { appointmentFormRef.value?.submitAppointmentForm() },
    validateIcon: PlusCircleIcon,
});

const resources = computed<Resource[]>(() => {
    if (getterResourceList.value === undefined) {
        return [];
    }
    return getterResourceList.value;
});

const clients = [
    {
        id: 1,
        name: 'Lucas',
        phone: '+351252862158',
        email: 'test@test.fr',
        totalVisits: 0,
    },
    {
        id: 2,
        name: 'João',
        phone: '+351252862158',
        email: 'test@test.fr',
        totalVisits: 2,
    },
    {
        id: 3,
        name: 'Maria',
        phone: '+351252862158',
        email: 'test@test.fr',
        totalVisits: 1,
    }
]

// Définition des événements
const appointments = ref<Appointment[]>([]);

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

const initiateAppointments = () => {
    if (!getterResourceList.value?.length) return;

    appointments.value = [];

    if (Math.random() < 0.5) {
        appointments.value.push({
            id: 1,
            resource: getterResourceList.value[0],
            client: clients[0],
            service: {
                id: 1,
                name: 'Cabelo + barba',
                price: 30,
            },
            startsAt: '04-04-2025 10:00',
            endsAt: '04-04-2025 11:00',
            createdAt: new Date().toISOString(),
            updatedAt: new Date().toISOString()
        });
    }
    appointments.value.push({
        id: 1,
        resource: getterResourceList.value[0],
        client: clients[1],
        service: {
            id: 1,
            name: 'Corte de cabelo',
            price: 30,
        },
        startsAt: '04-04-2025 08:00',
        endsAt: '04-04-2025 09:00',
        createdAt: new Date().toISOString(),
        updatedAt: new Date().toISOString()
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
</script>