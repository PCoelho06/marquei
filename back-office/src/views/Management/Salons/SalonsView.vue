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
                        <CoelhoButton :to="router.resolve({ name: 'GetSalon', params: { id: salon.id } }).href"
                            :icon="CogIcon">
                            Gerir
                        </CoelhoButton>
                        <CoelhoButton variant="danger" :icon="TrashIcon" @click="showModal = true">
                            Remover
                        </CoelhoButton>
                        <DefaultModal v-if="showModal" title="Remover o salão" :actionClose="() => showModal = false">
                            <template #content>
                                <p>Tem a certeza que deseja remover este salão ? Esta ação é irreversível.</p>
                            </template>
                            <template #actions>
                                <div class="flex space-x-4">
                                    <CoelhoButton variant="secondary" :icon="XMarkIcon" @click="showModal = false"
                                        class="w-full">Cancelar
                                    </CoelhoButton>
                                    <CoelhoButton variant="danger" :icon="TrashIcon"
                                        @click="handleRemoveSalon(salon.id)" class="w-full">Remover</CoelhoButton>
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
import { useRouter } from 'vue-router';

import { useSalonsStore } from '@/stores/salons';
import { storeToRefs } from 'pinia';
import DefaultCard from '@/components/Cards/DefaultCard.vue';
import DefaultModal from '@/components/Modals/DefaultModal.vue';
import ManagementLayout from '@/layouts/ManagementLayout.vue';
import { CoelhoButton } from '@/components';
import { CogIcon, TrashIcon, XMarkIcon } from '@heroicons/vue/24/solid';

const router = useRouter();

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