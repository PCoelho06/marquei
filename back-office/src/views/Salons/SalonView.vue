<template>
    <div class="container mx-auto" v-if="isReady">
        <h1 class="text-2xl font-semibold">Salão {{ getterSalon?.name }}</h1>
        <div class="grid grid-cols-3 m-4" v-if="isReady">
            <DefaultCard cardTitle="Informações gerais">
                <div class="flex flex-col py-2 px-4">
                    <p class="text-sm text-gray-500 my-2">{{ getterSalon?.address }} <br />
                        {{ getterSalon?.postalCode }}, {{ getterSalon?.city }}</p>
                    <p class="text-sm text-gray-500">Telefone: <a :href="'tel:' + getterSalon?.phone">{{
                        getterSalon?.phone }}</a></p>
                    <div class="flex space-x-4 my-2">
                        <!-- <DefaultButton value="Gerir" size="sm" @click="() => console.log(salon.id)" /> -->
                        <LinkButton :to="'/salons/' + getterSalon?.id + '/editar'" value="Editar" size="sm" />
                    </div>
                </div>
            </DefaultCard>
        </div>
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router';
import { storeToRefs } from 'pinia';

import { useSalonsStore } from '@/stores/salons';

import DefaultCard from '@/components/Cards/DefaultCard.vue';
import LinkButton from '@/components/Buttons/LinkButton.vue';

const route = useRoute();
const router = useRouter();

const salonsStore = useSalonsStore();
const { getterSalon } = storeToRefs(salonsStore);

const isReady = ref(false);

onMounted(() => {
    salonsStore.getSalon({ id: Number(route.params.id) });
    isReady.value = true;
});
</script>