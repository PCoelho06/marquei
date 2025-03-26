<template>
    <ManagementLayout>
        <div class="mx-auto">
            <CoelhoDataTable v-if="formattedSalonsList?.length" :items="formattedSalonsList" :columns="columnsSalons"
                :totalElements="getterSalonSettings?.totalElements" :first="getterSalonSettings?.first"
                :last="getterSalonSettings?.last" :totalPages="getterSalonSettings?.totalPages"
                @update:limit="query = { ...updateLimit(query, $event, fetchSalonList) }"
                @update:sort="query = { ...updateSort(query, $event, libQuerySort, fetchSalonList) }"
                @update:page="query = { ...updatePage(query, $event, getterSalonSettings?.totalPages ? getterSalonSettings.totalPages : 1, fetchSalonList) }">
                <template #actions>
                    <CoelhoButton variant="primary" :icon="PlusCircleIcon"
                        :to="router.resolve({ name: 'CreateSalon' }).href">
                        Adicionar um salão
                    </CoelhoButton>
                </template>

                <template #rowActions="{ item }">
                    <div class="flex space-x-2">
                        <CoelhoButton :to="router.resolve({ name: 'GetSalon', params: { id: item.id } }).href"
                            :icon="CogIcon" />
                        <CoelhoButton variant="danger" :icon="TrashIcon" @click="openModal(item)" />
                    </div>
                </template>
            </CoelhoDataTable>
            <CoelhoCard v-else size="full">
                <div class="flex flex-col justify-center items-center gap-4">
                    <p>Nenhum salão disponivel</p>
                    <CoelhoButton :icon="PlusCircleIcon" variant="primary"
                        :to="router.resolve({ name: 'CreateSalon' }).href">
                        Adicionar um salão
                    </CoelhoButton>
                </div>
            </CoelhoCard>
            <CoelhoModal v-model="showModal" v-if="selectedSalon" :title="'Remover o salão' + selectedSalon.name">
                <CoelhoText>Tem a certeza que deseja remover este salão ? Esta ação é irreversível</CoelhoText>
                <template #footer>
                    <CoelhoButton variant="secondary" :icon="XMarkIcon" @click="showModal = false" class="w-full">
                        Cancelar
                    </CoelhoButton>
                    <CoelhoButton variant="danger" :icon="TrashIcon"
                        @click="handleRemoveSalon(Number(selectedSalon.id))" class="w-full">Remover</CoelhoButton>
                </template>
            </CoelhoModal>
        </div>
    </ManagementLayout>
</template>

<script setup lang="ts">
import { onMounted, ref, computed } from 'vue';
import { storeToRefs } from 'pinia';
import { useRouter } from 'vue-router';

import { useSalonsStore } from '@/stores/salons';

import { engineQueries } from '@/composables/engineQueries';
import { columnsSalons } from '@/views/commons/composables/columnsResources';
import { formatters } from '@/utils';

import ManagementLayout from '@/layouts/ManagementLayout.vue';

import { CoelhoButton, CoelhoDataTable, CoelhoModal, CoelhoCard, CoelhoText } from '@/components';
import { TrashIcon, XMarkIcon, PlusCircleIcon, CogIcon } from '@heroicons/vue/24/solid';

import type { BaseQuery } from '@/types';
import type { Item } from '@/types';

const router = useRouter();
const { formatForRouter, updateLimit, updateSort, updatePage } = engineQueries();

const salonsStore = useSalonsStore();
const { getterSalonList, getterSalonSettings } = storeToRefs(salonsStore);

const libQuerySort = ['name', 'subscription']

const selectedSalon = ref<Item | undefined>();
const showModal = ref(false);
const query = ref<BaseQuery>({
    page: 1,
    limit: 10,
    sort: [],
});

const openModal = (item: Item) => {
    selectedSalon.value = item;
    showModal.value = true;
}

const formattedSalonsList = computed(() => {
    return getterSalonList.value?.map((salon) => {
        return {
            id: salon.id,
            name: salon.name,
            address: formatters.formatAddress(salon.address, salon.postalCode, salon.city),
            phone: formatters.formatPhone(salon.phone),
        }
    })
})

const fetchSalonList = async (args = {}) => {
    query.value = { ...query.value, ...args }

    await salonsStore.searchSalons({ httpQuery: query.value })

    router.push({ query: formatForRouter(query.value) });
}

onMounted(async () => {
    await fetchSalonList()
});

const handleRemoveSalon = async (id: number) => {
    await salonsStore.deleteSalon({ id })
    showModal.value = false;
}
</script>