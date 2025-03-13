<template>
    <ManagementLayout>
        <div class="container mx-auto">
            <h1 class="text-2xl font-semibold">Salões</h1>
            <div class="grid grid-cols-3 gap-4 m-4" v-if="isReady">
                <DefaultCard v-for="salon in getterSalons" :key="salon.id" :cardTitle="salon.name">
                    <template #default>
                        <div class="flex flex-col">
                            <p class="text-sm text-gray-500 my-2">{{ salon.address }} <br />
                                {{ salon.postalCode }}, {{ salon.city }}</p>
                            <p class="text-sm text-gray-500">Telefone: <a :href="'tel:' + salon.phone">{{ salon.phone
                            }}</a>
                            </p>
                        </div>
                    </template>
                    <template #action>
                        <LinkButton :to="{ name: 'GetSalon', params: { id: salon.id } }" value="Gerir" size="sm" />
                        <DefaultButton value="Remover" type="danger" size="sm" @click="showModal = true" />
                        <DefaultModal v-if="showModal" title="Remover o salão" :actionClose="() => showModal = false">
                            <template #content>
                                <p>Tem a certeza que deseja remover este salão ? Esta ação é irreversível.</p>
                            </template>
                            <template #actions>
                                <div class="flex space-x-4">
                                    <DefaultButton value="Cancelar" type="primary" size="sm"
                                        @click="showModal = false" />
                                    <DefaultButton value="Remover" type="danger" size="sm"
                                        @click="handleRemoveSalon(salon.id)" />
                                </div>
                            </template>
                        </DefaultModal>
                    </template>
                </DefaultCard>
            </div>
        </div>
    </ManagementLayout>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';

import { useSalonsStore } from '@/stores/salons';
import { storeToRefs } from 'pinia';
import DefaultCard from '@/components/Cards/DefaultCard.vue';
import LinkButton from '@/components/Buttons/LinkButton.vue';
import DefaultButton from '@/components/Buttons/DefaultButton.vue';
import DefaultModal from '@/components/Modals/DefaultModal.vue';
import ManagementLayout from '@/layouts/ManagementLayout.vue';

const salonsStore = useSalonsStore();
const { getterSalons } = storeToRefs(salonsStore);

const isReady = ref(false);
const showModal = ref(false);

onMounted(() => {
    salonsStore.listSalons();
    isReady.value = true;
});

const handleRemoveSalon = async (id: number) => {
    await salonsStore.deleteSalon({ id })
    showModal.value = false;
}
</script>