<template>
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
                        <LinkButton :to="'/salons/' + getterSalon?.id + '/editar'" value="Editar" size="sm" />
                    </div>
                </template>
            </DefaultCard>
            <DefaultCard cardTitle="Horários de funcionamento">
                <template #default>
                    <table class="table-auto">
                        <tbody>
                            <tr v-for="dayBusinessHours in getterBusinessHours" :key="dayBusinessHours.id">
                                <th class="px-4">{{ mapNumberToDayOfWeekShort(Number(dayBusinessHours.dayOfWeek)) }}
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
                        <LinkButton :to="'/salons/' + getterSalon?.id + '/handleBusinessHours'"
                            :value="getterBusinessHours?.length ? 'Editar' : 'Adicionar horários'" size="sm" />
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
                                        <InputGroup type="number" label="Preço" id="service-price" placeholder="Preço"
                                            autocomplete="off" v-model="service.price" />
                                        €
                                    </div>
                                </td>
                                <td class="px-2">
                                    <div class="flex gap-4">
                                        <DefaultButton value="Guardar" size="sm" @click="saveService(service)" />
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
                                        <DefaultButton value="Editar" size="sm" @click="service.isEditing = true" />
                                        <ModalButton value="Eliminar" size="sm" type="danger" :modalTexts
                                            :action="deleteService(service.id)" />
                                    </div>
                                </td>
                            </template>
                        </tr>
                    </tbody>
                </table>
            </template>
            <template #action>
                <div class="flex space-x-2">
                    <DefaultButton value="Adicionar uma prestação" size="sm" @click="addService" />
                </div>
            </template>
        </DefaultCard>
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router';
import { storeToRefs } from 'pinia';

import { useSalonsStore } from '@/stores/salons';

import { mapNumberToDayOfWeekShort } from '@/utils';
import { api } from '@/api';

import type { Service } from '@/types/salons';

import DefaultCard from '@/components/Cards/DefaultCard.vue';
import LinkButton from '@/components/Buttons/LinkButton.vue';
import DefaultButton from '@/components/Buttons/DefaultButton.vue';
import ModalButton from '@/components/Buttons/ModalButton.vue';
import InputGroup from '@/components/Forms/InputGroup.vue';

const route = useRoute();
const router = useRouter();

const salonsStore = useSalonsStore();
const { getterSalon, getterBusinessHours } = storeToRefs(salonsStore);

const isReady = ref(false);
const services = ref<Service[] | undefined>();

const modalTexts = {
    title: 'Remover a prestação de serviço',
    content: 'Tem a certeza que deseja remover esta prestação ? Esta ação é irreversível.',
    validate: 'Remover',
    dismiss: 'Cancelar',
}

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
    return () => {
        api().services.delete({ id }).then(() => {
            services.value = services.value?.filter((service) => service.id !== id);
        });
    }
}

onMounted(async () => {
    await salonsStore.getSalon({ id: Number(route.params.id) });
    await salonsStore.getBusinessHours({ id: Number(route.params.id) });
    await salonsStore.getServices({ id: Number(route.params.id) });

    services.value = salonsStore.getterServices;
    services.value = services.value?.map((service) => {
        return {
            ...service,
            isEditing: false,
        }
    });

    isReady.value = true;
});
</script>