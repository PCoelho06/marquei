<template>
    <div class="container mx-auto">
        <h1 class="text-2xl font-semibold">Salões</h1>
        <div class="grid grid-cols-3 m-4" v-if="isReady">
            <DefaultCard v-for="salon in getterSalons" :key="salon.id" :cardTitle="salon.name">
                <div class="flex flex-col py-2 px-4">
                    <p class="text-sm text-gray-500 my-2">{{ salon.address }} <br />
                        {{ salon.postalCode }}, {{ salon.city }}</p>
                    <p class="text-sm text-gray-500">Telefone: <a :href="'tel:' + salon.phone">{{ salon.phone }}</a></p>
                    <div class="flex space-x-4 my-2">
                        <LinkButton :to="'/salons/' + salon.id" value="Gerir" size="sm" />
                        <DefaultButton value="Remover" type="danger" size="sm"
                            @click="salonsStore.deleteSalon({ id: salon.id })" />
                    </div>
                </div>
            </DefaultCard>
        </div>
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';

import { useSalonsStore } from '@/stores/salons';
import { storeToRefs } from 'pinia';
import DefaultCard from '@/components/Cards/DefaultCard.vue';
import DefaultButton from '@/components/Buttons/DefaultButton.vue';
import LinkButton from '@/components/Buttons/LinkButton.vue';

const salonsStore = useSalonsStore();
const { getterSalons } = storeToRefs(salonsStore);

const isReady = ref(false);

onMounted(() => {
    salonsStore.listSalons();
    isReady.value = true;
});
</script>