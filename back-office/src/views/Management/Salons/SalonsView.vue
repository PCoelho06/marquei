<template>
    <div class="container mx-auto">
        <h1 class="text-2xl font-semibold">Salões</h1>
        <div class="grid grid-cols-3 gap-4 m-4" v-if="isReady">
            <DefaultCard v-for="salon in getterSalons" :key="salon.id" :cardTitle="salon.name">
                <template #default>
                    <div class="flex flex-col">
                        <p class="text-sm text-gray-500 my-2">{{ salon.address }} <br />
                            {{ salon.postalCode }}, {{ salon.city }}</p>
                        <p class="text-sm text-gray-500">Telefone: <a :href="'tel:' + salon.phone">{{ salon.phone }}</a>
                        </p>
                    </div>
                </template>
                <template #action>
                    <div class="flex space-x-4">
                        <LinkButton :to="{ name: 'GetSalon', params: { id: salon.id } }" value="Gerir" size="sm" />
                        <ModalButton value="Remover" type="danger" size="sm" :modalTexts
                            :action="() => salonsStore.deleteSalon({ id: salon.id })" />
                    </div>
                </template>
            </DefaultCard>
        </div>
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';

import { useSalonsStore } from '@/stores/salons';
import { storeToRefs } from 'pinia';
import DefaultCard from '@/components/Cards/DefaultCard.vue';
import LinkButton from '@/components/Buttons/LinkButton.vue';
import ModalButton from '@/components/Buttons/ModalButton.vue';

const salonsStore = useSalonsStore();
const { getterSalons } = storeToRefs(salonsStore);

const isReady = ref(false);

const modalTexts = {
    title: 'Remover o salão',
    content: 'Tem a certeza que deseja remover este salão ? Esta ação é irreversível.',
    validate: 'Remover',
    dismiss: 'Cancelar',
}

onMounted(() => {
    salonsStore.listSalons();
    isReady.value = true;
});
</script>