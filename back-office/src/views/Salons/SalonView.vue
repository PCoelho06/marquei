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
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router';
import { storeToRefs } from 'pinia';

import { useSalonsStore } from '@/stores/salons';

import { mapNumberToDayOfWeekShort } from '@/utils';

import DefaultCard from '@/components/Cards/DefaultCard.vue';
import LinkButton from '@/components/Buttons/LinkButton.vue';

const route = useRoute();
const router = useRouter();

const salonsStore = useSalonsStore();
const { getterSalon, getterBusinessHours } = storeToRefs(salonsStore);

const isReady = ref(false);

onMounted(async () => {
    await salonsStore.getSalon({ id: Number(route.params.id) });
    await salonsStore.getBusinessHours({ id: Number(route.params.id) });
    console.log("🚀 ~ getterBusinessHours:", getterBusinessHours.value);
    isReady.value = true;
});
</script>