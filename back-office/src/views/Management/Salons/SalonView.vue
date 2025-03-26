<template>
    <ManagementLayout>
        <div class="mx-auto">
            <div class="grid grid-cols-3 gap-4 my-4">
                <PanelInformations />
                <PanelBusinessHours />
                <PanelSubscription :salonId="Number(route.params.id)"
                    @delete:subscription="openModal('delete:subscription')" />
            </div>

            <div class="mx-auto">
                <CoelhoDataTable v-if="formattedServicesList?.length" :items="formattedServicesList"
                    :columns="columnsServices" :totalElements="getterServiceSettings?.totalElements"
                    :first="getterServiceSettings?.first" :last="getterServiceSettings?.last"
                    :totalPages="getterServiceSettings?.totalPages"
                    @update:limit="query = { ...updateLimit(query, $event, fetchServicesList) }"
                    @update:sort="query = { ...updateSort(query, $event, libQuerySort, fetchServicesList) }"
                    @update:page="query = { ...updatePage(query, $event, getterServiceSettings?.totalPages ? getterServiceSettings.totalPages : 1, fetchServicesList) }">
                    <template #actions>
                        <CoelhoButton variant="primary" :icon="PlusCircleIcon" @click="openModal('create:service')">
                            Adicionar um serviço
                        </CoelhoButton>
                    </template>

                    <template #rowActions="{ item }">
                        <div class="flex space-x-2">
                            <CoelhoButton variant="primary" size="sm" :icon="PencilIcon"
                                @click="openModal('edit:service', item.id)" />
                            <CoelhoButton variant="danger" size="sm" :icon="TrashIcon"
                                @click="openModal('delete:service', item.id)" />
                        </div>
                    </template>
                </CoelhoDataTable>
                <CoelhoCard v-else size="full">
                    <div class="flex flex-col justify-center items-center gap-4">
                        <p>Nenhum serviço disponivel</p>
                        <CoelhoButton :icon="PlusCircleIcon" variant="primary" @click="openModal('create:service')">
                            Adicionar um serviço
                        </CoelhoButton>
                    </div>
                </CoelhoCard>
            </div>
        </div>
        <CoelhoModal v-model="showModal" :title="modal?.title">
            <ServiceForm v-if="modal?.content === 'ServiceForm'" :service="modal.props" ref="serviceFormRef"
                @submit="handleServiceSubmit" />
            <CoelhoText v-else>{{ modal?.content }}</CoelhoText>
            <template #footer>
                <CoelhoButton :icon="XMarkIcon" @click="showModal = false" variant="secondary">
                    {{ modal?.dismiss }}
                </CoelhoButton>
                <CoelhoButton :icon="modal?.validateIcon" @click="validateModalAction"
                    :variant="modal?.validateVariant">
                    {{ modal?.validate }}
                </CoelhoButton>
            </template>
        </CoelhoModal>
    </ManagementLayout>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter, onBeforeRouteLeave } from 'vue-router';
import { storeToRefs } from 'pinia';

import { useSalonsStore } from '@/stores/salons';
import { useServicesStore } from '@/stores/services';

import { api } from '@/api';
import { formatters } from '@/utils';
import { engineQueries } from '@/composables/engineQueries';
import { columnsServices } from '@/views/commons/composables/columnsResources';
import ServiceForm from './lib/ServiceForm.vue';

import type { ComponentPublicInstance } from 'vue';
import type { ModalContent } from '@/types';
import type { Service } from '@/types/salons';
import type { ServiceQuery, ServiceCreatePayload, ServiceUpdatePayload } from '@/types/services';

import ManagementLayout from '@/layouts/ManagementLayout.vue';
import PanelInformations from './views/panels/PanelInformations.vue';
import PanelBusinessHours from './views/panels/PanelBusinessHours.vue';
import PanelSubscription from './views/panels/PanelSubscription.vue';

import { CoelhoModal, CoelhoButton, CoelhoDataTable, CoelhoCard, CoelhoText } from '@/components';
import { PencilIcon, PlusCircleIcon, TrashIcon, XMarkIcon } from '@heroicons/vue/24/solid';

interface ManageServiceModalContent extends ModalContent {
    props?: Service;
}

const { formatForRouter, updateLimit, updatePage, updateSort } = engineQueries()

const route = useRoute();
const router = useRouter();

const salonsStore = useSalonsStore();
const servicesStore = useServicesStore();

const { getterServiceList, getterServiceSettings, getterQuery } = storeToRefs(servicesStore);

const libQuerySort = ['name', 'duration', 'price']

const serviceFormRef = ref<ComponentPublicInstance & { submitServiceForm: () => void }>();
const showModal = ref(false);
const modal = ref<ManageServiceModalContent>();
const query = ref<ServiceQuery>({
    salonId: Number(route.params.id),
    page: 1,
    limit: 10,
    sort: [],
});

const createServiceModalContent: ManageServiceModalContent = {
    title: 'Adicionar uma prestação de serviço',
    content: 'ServiceForm',
    validate: 'Adicionar',
    validateVariant: 'primary',
    validateIcon: PlusCircleIcon,
    dismiss: 'Cancelar',
}
const editServiceModalContent: ManageServiceModalContent = {
    title: 'Editar uma prestação de serviço',
    content: 'ServiceForm',
    validate: 'Editar',
    validateVariant: 'primary',
    validateIcon: PencilIcon,
    dismiss: 'Cancelar',
}
const deleteServiceModalContent: ManageServiceModalContent = {
    title: 'Remover a prestação de serviço',
    content: 'Tem a certeza que deseja remover esta prestação ? Esta ação é irreversível.',
    validate: 'Remover',
    validateVariant: 'danger',
    validateIcon: TrashIcon,
    dismiss: 'Cancelar',
}

const validateModalAction = () => {
    if (modal.value?.content === 'ServiceForm') {
        serviceFormRef.value?.submitServiceForm();
    } else if (modal.value?.action) {
        modal.value.action();
    }
}

const handleServiceSubmit = (serviceData: ServiceCreatePayload | ServiceUpdatePayload) => {
    showModal.value = false;
    serviceData.salonId = Number(route.params.id);
    serviceData.price = serviceData.price * 100;
    serviceData.duration = Number(serviceData.duration);

    if ('id' in serviceData && serviceData.id) {
        api().services.update(serviceData).then(() => {
            fetchServicesList();
        });
    } else {
        api().services.create(serviceData).then(() => {
            fetchServicesList();
        });
    }
};

const formattedServicesList = computed(() => {
    return getterServiceList.value?.map((service) => {
        return {
            id: service.id,
            name: service.name,
            description: service.description,
            duration: formatters.formatDuration(service.duration),
            price: formatters.formatPrice(service.price),
        }
    })
})

const deleteService = (id: number) => {
    api().services.delete({ id }).then(() => {
        fetchServicesList();
        showModal.value = false;
    });
}

const fetchServicesList = async (args = {}) => {
    query.value = { ...query.value, ...args }

    await servicesStore.searchServices({ httpQuery: query.value })

    servicesStore.setQuery(query.value)

    router.push({ query: formatForRouter(query.value) });
}

onMounted(() => {
    const httpQuery = getterQuery.value
        ? { ...getterQuery.value, ...router.currentRoute.value.query }
        : router.currentRoute.value.query;

    Promise.all([
        salonsStore.getSalon({ id: Number(route.params.id) }),
        fetchServicesList(httpQuery),
    ])
});

const openModal = (type: string, data?: any) => {
    switch (type) {
        case 'create:service':
            modal.value = createServiceModalContent;
            break;
        case 'edit:service':
            modal.value = editServiceModalContent;
            modal.value.props = getterServiceList.value?.find((service) => service.id === data);
            break;
        case 'delete:service':
            modal.value = deleteServiceModalContent;
            modal.value.action = () => deleteService(data);
            break;
        default:
            break;
    }
    showModal.value = true;
}

onBeforeRouteLeave((leaveGuard) => {
    if (!leaveGuard.path.includes("/saloes")) servicesStore.setQuery(undefined);
});
</script>