<template>
    <div v-if="isReady" class="flex flex-col gap-8 w-full">
        <CoelhoDateTimePicker :initialValue="localAppointment.startsAt" label="Data e hora"
            :error="validationAppointmentErrors.startsAt" />
        <div class="flex flex-col col-start-1 sm:col-span-2">
            <CoelhoCheckbox v-model="isNewClient" variant="switch" size="sm" label="Novo Cliente" class="mb-2" />
            <CoelhoCard v-if="isNewClient" size="full" title="Novo cliente">
                <div class="grid grid-cols-2 gap-8">
                    <CoelhoInput v-model="client.firstName" label="Apelido do cliente" :leftIcon="UserIcon"
                        :error="validationClientErrors.firstname" :required="true" />
                    <CoelhoInput v-model="client.lastName" label="Nome do cliente" :leftIcon="UsersIcon"
                        :error="validationClientErrors.lastname" :required="true" />
                    <CoelhoInput v-model="client.phone" label="Contacto do cliente" :leftIcon="PhoneIcon"
                        :error="validationClientErrors.phone" :required="true" @input="handlePhoneChange" />
                    <CoelhoInput v-model="client.email" label="Email do cliente" :leftIcon="EnvelopeIcon"
                        :error="validationClientErrors.email" :required="true" />
                </div>
            </CoelhoCard>
            <CoelhoCard v-else size="full" title="Cliente registado">
                <ClientsFilters @submit="fetchClientList" />
                <CoelhoDataTable v-if="formattedClientList?.length" :items="formattedClientList"
                    :columns="columnsClients" :totalElements="getterClientSettings?.totalElements"
                    :first="getterClientSettings?.first" :last="getterClientSettings?.last"
                    :totalPages="getterClientSettings?.totalPages" :selectable="true"
                    @update:selected="localAppointment.clientId = $event[0] && 'id' in $event[0] ? Number($event[0].id) : 0"
                    @update:limit="query = { ...updateLimit(query, $event, fetchClientList) }"
                    @update:sort="query = { ...updateSort(query, $event, libQuerySort, fetchClientList) }"
                    @update:page="query = { ...updatePage(query, $event, getterClientSettings?.totalPages ? getterClientSettings.totalPages : 1, fetchClientList) }">
                    <template #rowActions="{ item }">
                        <div class="flex space-x-2">
                            <CoelhoButton variant="primary" size="sm" :icon="PencilIcon"
                                :to="router.resolve({ name: 'GetResource', params: { id: item.id } }).href" />
                        </div>
                    </template>
                </CoelhoDataTable>
            </CoelhoCard>
            <CoelhoText v-if="validationAppointmentErrors.clientId" size="sm" color="danger" class="mt-1">
                {{ validationAppointmentErrors.clientId }}
            </CoelhoText>
        </div>
        <div class="grid grid-cols-1 gap-8 m-6 sm:grid-cols-2">
            <CoelhoSelect v-model="localAppointment.serviceId" :leftIcon="SparklesIcon" label="Serviço"
                :searchable="true" :error="validationAppointmentErrors.serviceId" :options="serviceOptions"
                :required="true" />
            <CoelhoSelect v-model="localAppointment.resourceId" :leftIcon="RectangleGroupIcon" label="Recurso"
                :searchable="true" :error="validationAppointmentErrors.resourceId" :options="resourceOptions"
                :required="true" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref, computed } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { storeToRefs } from 'pinia';

import { mappers, validators, formatters } from '@/utils';
import { engineQueries } from '@/composables/engineQueries';
import { columnsClients } from '@/views/commons/composables/columns/columnsClients';

import { useServicesStore } from '@/stores/services';
import { useResourcesStore } from '@/stores/resources';
import { useClientsStore } from '@/stores/clients';

import { CoelhoSelect, CoelhoInput, CoelhoCheckbox, CoelhoDateTimePicker, CoelhoDataTable, CoelhoButton, CoelhoText } from '@/components';
import { UserIcon, PhoneIcon, RectangleGroupIcon, SparklesIcon, PencilIcon, EnvelopeIcon, TagIcon, UsersIcon } from '@heroicons/vue/24/solid';

import type { SelectOption } from '@/types';
import type { AppointmentCreatePayload, AppointmentUpdatePayload } from '@/types/appointments';
import ClientsFilters from '../filters/ClientsFilters.vue';
import type { ClientCreatePayload, ClientQuery } from '@/types/clients';
import CoelhoCard from '@/components/organisms/CoelhoCard.vue';

const props = defineProps<{ appointment?: AppointmentCreatePayload | AppointmentUpdatePayload }>();
const emits = defineEmits<{
    (e: 'submit', payload: { appointment: AppointmentCreatePayload; client: ClientCreatePayload } | AppointmentCreatePayload): void;
}>();

const route = useRoute();
const router = useRouter();

const { updateLimit, updatePage, updateSort } = engineQueries();

const servicesStore = useServicesStore();
const resourcesStore = useResourcesStore();
const clientsStore = useClientsStore();
const { getterClientList, getterClientSettings } = storeToRefs(clientsStore);

const isReady = ref(false);
const isNewClient = ref(false);
const client = ref<ClientCreatePayload>({
    firstName: '',
    lastName: '',
    phone: '',
    email: '',
})
const serviceOptions = ref<SelectOption[]>([]);
const resourceOptions = ref<SelectOption[]>([]);
const query = ref<ClientQuery>({
    page: 1,
    limit: 10,
    sort: [],
});

const localAppointment = ref<AppointmentCreatePayload | AppointmentUpdatePayload>({
    ...(props.appointment && 'id' in props.appointment ? { id: props.appointment.id } : {}),
    resourceId: props.appointment?.resourceId || 0,
    clientId: props.appointment?.clientId || 0,
    serviceId: props.appointment?.serviceId || 0,
    salonId: Number(route.params.salonId),
    startsAt: props.appointment?.startsAt || '',
});
const validationAppointmentErrors = ref({
    resourceId: '',
    clientId: '',
    serviceId: '',
    startsAt: '',
    endsAt: '',
})
const validationClientErrors = ref({
    firstname: '',
    lastname: '',
    phone: '',
    email: '',
})

const libQuerySort = ['name', 'phone']

const formattedClientList = computed(() => {
    return getterClientList.value?.map((client) => {
        return {
            id: client.id,
            name: `${client.firstName} ${client.lastName}`,
            phone: formatters.formatPhone(client.phone),
        }
    })
})

const submitAppointmentForm = () => {
    validationAppointmentErrors.value = validators.appointments.validateAppointmentsData(localAppointment.value);
    if (Object.values(validationAppointmentErrors.value).some((error) => error !== '')) {
        return;
    }

    if (isNewClient.value) {
        validationClientErrors.value = validators.clients.validateClientsData(client.value);
        if (Object.values(validationClientErrors.value).some((error) => error !== '')) {
            return;
        }
    }

    const data = isNewClient.value
        ? {
            appointment: localAppointment.value,
            client: {
                ...client.value,
                phone: formatters.formatPhone(client.value.phone),
            },
        }
        : localAppointment.value;

    emits('submit', data);
};

defineExpose({ submitAppointmentForm });

onMounted(async () => {
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

const fetchClientList = async (args = {}) => {
    query.value = { ...query.value, ...args }

    await clientsStore.searchClients({ httpQuery: query.value })

    clientsStore.setQuery(query.value)
}

const handlePhoneChange = () => {
    validationClientErrors.value.phone = '';

    client.value.phone = formatters.formatPhone(client.value.phone);
};
</script>