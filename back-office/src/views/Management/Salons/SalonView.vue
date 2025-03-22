<template>
    <ManagementLayout>
        <div class="container mx-auto" v-if="isReady">
            <h1 class="text-2xl font-semibold">{{ getterSalon?.name }}</h1>
            <div class="grid grid-cols-3 gap-4 my-4" v-if="isReady">
                <DefaultCard cardTitle="Informações gerais">
                    <template #default>
                        <div class="flex flex-col py-2 px-4">
                            <p class="text-sm text-gray-500 my-2">{{ getterSalon?.address }} <br />
                                {{ getterSalon?.postalCode }}, {{ getterSalon?.city }}</p>
                            <p class="text-sm text-gray-500">Telefone: <a :href="'tel:' + getterSalon?.phone">{{
                                getterSalon?.phone }}</a></p>
                        </div>
                    </template>
                    <template #action>
                        <div class="flex space-x-4">
                            <CoelhoButton :icon="PencilIcon"
                                :to="router.resolve({ name: 'EditSalon', params: { id: getterSalon?.id } }).href">
                                Editar
                            </CoelhoButton>
                        </div>
                    </template>
                </DefaultCard>
                <DefaultCard cardTitle="Horários de funcionamento">
                    <template #default>
                        <table class="table-auto">
                            <tbody>
                                <tr v-for="dayBusinessHours in getterBusinessHours" :key="dayBusinessHours.id">
                                    <th class="px-4">{{
                                        mappers.mapNumberToDayOfWeekShort(Number(dayBusinessHours.dayOfWeek)) }}
                                    </th>
                                    <td class="px-2">{{ dayBusinessHours.startTime }}</td>
                                    <td class="text-center"> - </td>
                                    <td class="px-2">{{ dayBusinessHours.endTime }}</td>
                                </tr>
                            </tbody>
                        </table>
                    </template>
                    <template #action>
                        <div class="flex space-x-4">
                            <CoelhoButton :icon="PencilIcon"
                                :to="router.resolve({ name: 'HandleBusinessHours', params: { id: getterSalon?.id } }).href">
                                {{ getterBusinessHours?.length ? 'Editar' : 'Adicionar horários' }}
                            </CoelhoButton>
                        </div>
                    </template>
                </DefaultCard>
                <DefaultCard cardTitle="Configurações da subscrição">
                    <template #default>
                        <div v-if="getterSubscription" class="flex flex-col py-2 px-4">
                            <p class="text-sm text-gray-500">Subscrição: Gestão</p>
                            <p class="text-sm text-gray-500">Estado: {{ getterStripeSubscription['status']
                                }}</p>
                            <p class="text-sm text-gray-500">Proxima faturação :
                                {{
                                    formatters.formatDateFromTimestamp(getterStripeSubscription['current_period_end'])
                                }}
                            </p>
                        </div>
                        <div v-else>
                            <div class="flex flex-col py-2 px-4">
                                <p class="text-sm text-gray-500">Subscrição: Não configurada</p>
                            </div>
                        </div>
                    </template>
                    <template #action>
                        <div v-if="getterSubscription" class="flex w-full justify-center space-x-4">
                            <CoelhoButton :icon="ArrowPathRoundedSquareIcon"
                                :to="router.resolve({ name: 'HandleForfait', params: { id: getterSalon?.id } }).href">
                                Mudar o plano</CoelhoButton>
                            <CoelhoButton :icon="XMarkIcon" @click="openModal('delete:subscription')" variant="danger">
                                Cancelar subscrição
                            </CoelhoButton>
                        </div>
                        <div v-else class="flex space-x-4">
                            <CoelhoButton :icon="ShoppingCartIcon"
                                :to="router.resolve({ name: 'HandleForfait', params: { id: getterSalon?.id } }).href">
                                Subscrever agora
                            </CoelhoButton>
                        </div>
                    </template>
                </DefaultCard>
            </div>
            <DefaultCard cardTitle="Prestações de serviços">
                <template #default>
                    <table class="table-auto w-full">
                        <tbody>
                            <tr v-for="service in services" :key="service.id">
                                <template v-if="service.isEditing">
                                    <td class="px-4">
                                        <InputGroup type="text" label="Nome" id="service-name" placeholder="Nome"
                                            autocomplete="off" v-model="service.name" />
                                        <InputGroup type="text" label="Descrição" id="service-description"
                                            placeholder="Descrição" autocomplete="off" v-model="service.description" />
                                    </td>
                                    <td class="px-2">
                                        <div class="flex items-center gap-2">
                                            <InputGroup type="number" label="Duração" id="service-duration"
                                                placeholder="Duração" autocomplete="off" v-model="service.duration" />
                                            min
                                        </div>
                                    </td>
                                    <td class="px-2">
                                        <div class="flex items-center gap-2">
                                            <InputGroup type="number" label="Preço" id="service-price"
                                                placeholder="Preço" autocomplete="off" v-model="service.price" />
                                            €
                                        </div>
                                    </td>
                                    <td class="px-2">
                                        <div class="flex gap-4">
                                            <CoelhoButton :icon="BookmarkSquareIcon" @click="saveService(service)">
                                                Guardar</CoelhoButton>
                                        </div>
                                    </td>
                                </template>
                                <template v-else>
                                    <td class="px-4">
                                        {{ service.name }} <br />
                                        <span class="text-sm text-gray-500">{{ service.description }}</span>
                                    </td>
                                    <td class="px-2">{{ service.duration }}min</td>
                                    <td class="px-2">{{ service.price }}€</td>
                                    <td class="px-2">
                                        <div class="flex gap-4">
                                            <CoelhoButton :icon="PencilIcon" @click="service.isEditing = true">Editar
                                            </CoelhoButton>
                                            <CoelhoButton :icon="TrashIcon"
                                                @click="openModal('delete:service', service.id)" variant="danger">
                                                Eliminar
                                            </CoelhoButton>
                                        </div>
                                    </td>
                                </template>
                            </tr>
                        </tbody>
                    </table>
                </template>
                <template #action>
                    <div class="flex space-x-2">
                        <CoelhoButton :icon="PlusCircleIcon" @click="addService">Adicionar uma prestação</CoelhoButton>
                    </div>
                </template>
            </DefaultCard>
            <CoelhoModal v-model="isModalOpen" :title="modal?.title">
                <p>{{ modal?.content }}</p>
                <template #footer>
                    <CoelhoButton @click="isModalOpen = false" variant="secondary">
                        {{ modal?.dismiss }}
                    </CoelhoButton>
                    <CoelhoButton @click="modal?.action" variant="danger">
                        {{ modal?.validate }}
                    </CoelhoButton>
                </template>
            </CoelhoModal>
        </div>
    </ManagementLayout>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router';
import { storeToRefs } from 'pinia';

import { useSalonsStore } from '@/stores/salons';
import { useSubscriptionsStore } from '@/stores/subscriptions';

import { mappers, formatters } from '@/utils';
import { api } from '@/api';

import type { Service } from '@/types/salons';
import type { SubscriptionCancelPayload } from '@/types/subscriptions'

import DefaultCard from '@/components/Cards/DefaultCard.vue';
import { CoelhoModal, CoelhoButton } from '@/components';
import InputGroup from '@/components/Forms/InputGroup.vue';
import ManagementLayout from '@/layouts/ManagementLayout.vue';
import type { ModalContent } from '@/types';
import { ArrowPathRoundedSquareIcon, BookmarkSquareIcon, PencilIcon, PlusCircleIcon, ShoppingCartIcon, TrashIcon, XMarkIcon } from '@heroicons/vue/24/solid';

const route = useRoute();
const router = useRouter();

const salonsStore = useSalonsStore();
const subscriptionsStore = useSubscriptionsStore();
const { getterSalon, getterBusinessHours } = storeToRefs(salonsStore);
const { getterSubscription, getterStripeSubscription } = storeToRefs(subscriptionsStore);

const isReady = ref(false);
const isModalOpen = ref(false);
const services = ref<Service[] | undefined>();

const deleteServiceModalContent = {
    title: 'Remover a prestação de serviço',
    content: 'Tem a certeza que deseja remover esta prestação ? Esta ação é irreversível.',
    validate: 'Remover',
    dismiss: 'Cancelar',
}
const deleteSubscriptionModalContent = {
    title: 'Cancelar subscrição',
    content: 'Tem a certeza que deseja cancelar sua subscrição ? Esta ação é irreversível.',
    validate: 'Sim, cancelar',
    dismiss: 'Não, manter',
}

const modal = ref<ModalContent>();

const addService = () => {
    services.value?.push({
        id: 0,
        name: '',
        description: '',
        duration: 0,
        price: 0,
        isEditing: true,
    });
};

const saveService = (service: Service) => {
    if (service) {
        if (service.id === 0) {
            api().services.create({
                salonId: Number(route.params.id),
                name: service.name,
                description: service.description,
                duration: Number(service.duration),
                price: Number(service.price),
            }).then((response) => {
                service.id = response.data.id;
                service.isEditing = false;
            });
        } else {
            api().services.update({
                id: service.id,
                name: service.name,
                description: service.description,
                duration: Number(service.duration),
                price: Number(service.price),
                salonId: Number(route.params.id),
            }).then(() => {
                service.isEditing = false;
            });
        }
    }
}

const deleteService = (id: number) => {
    api().services.delete({ id }).then(() => {
        services.value = services.value?.filter((service) => service.id !== id);
        isModalOpen.value = false;
    });
}

const cancelSubscription = () => {
    const payload: SubscriptionCancelPayload = {
        subscriptionId: String(getterSubscription.value?.id),
        stripeSubscriptionId: getterStripeSubscription.value?.id,
    };
    subscriptionsStore.cancelSubscription(payload).then(() => {
        isModalOpen.value = false;
    });
}

onMounted(() => {
    Promise.all([salonsStore.getBusinessHours({ id: Number(route.params.id) }), salonsStore.getServices({ id: Number(route.params.id) }), subscriptionsStore.getSubscription({ id: Number(route.params.id) })]).then(() => {
        console.log("🚀 ~ Promise.all ~ StripeSubscription:", getterStripeSubscription.value)
        services.value = salonsStore.getterServices;
        services.value = services.value?.map((service) => {
            return {
                ...service,
                isEditing: false,
            }
        });

        isReady.value = true;
    });
});

const openModal = (type: string, data?: any) => {
    if (type === 'delete:service') {
        modal.value = deleteServiceModalContent;
        modal.value.action = () => deleteService(data);
    } else if (type === 'delete:subscription') {
        modal.value = deleteSubscriptionModalContent;
        modal.value.action = cancelSubscription;
    }
    isModalOpen.value = true;
}
</script>