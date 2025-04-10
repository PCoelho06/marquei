<template>
    <CoelhoHeading :level="2" size="xs">
        Filtros
    </CoelhoHeading>
    <form @submit.prevent="submit()">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4" id="automResourcesAll">
            <CoelhoInput v-model="httpQuery.name" label="Nome" placeholder="Nome do cliente"
                :leftIcon="ChatBubbleLeftEllipsisIcon" class="w-full" />
            <CoelhoInput v-model="httpQuery.phone" label="Contacto" placeholder="Numero de telefone"
                :leftIcon="PhoneIcon" class="w-full" />
        </div>
        <div class="mt-4 flex gap-4 justify-end">
            <CoelhoButton v-if="hasFilters" variant="danger" :icon="XCircleIcon" @click="clearFilters">
                Apagar filtros
            </CoelhoButton>
        </div>
    </form>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from "vue";
import { storeToRefs } from "pinia";

import { CoelhoButton, CoelhoCard, CoelhoHeading, CoelhoInput } from "@/components";

import { engineQueries } from "@/composables/engineQueries";
import { filtersClients } from "../composables/query";

import { useClientsStore } from "@/stores/clients";

import type { ClientFilters } from "@/types/clients";
import { ChatBubbleLeftEllipsisIcon, PhoneIcon, XCircleIcon } from "@heroicons/vue/24/solid";

const { formatForRouter, formatFromRouter } = engineQueries();

const clientsStore = useClientsStore();
const { getterQuery } = storeToRefs(clientsStore);

const emits = defineEmits(["submit"]);
defineProps({ loading: Boolean });

const httpQuery = ref<ClientFilters>({
    name: "",
    phone: "",
});
const hasFilters = ref(false);

const submit = () => {
    emits("submit", formatForRouter({ ...httpQuery.value, page: 1 }));
};

const setQueries = () => {
    if (getterQuery.value) {
        httpQuery.value = {
            ...httpQuery.value,
            ...formatFromRouter(getterQuery.value, filtersClients.value)
        };
    }
};

onMounted(async () => {
    await clientsStore.searchClients({ httpQuery: {} });
    setQueries();
})

watch(httpQuery, () => {
    hasFilters.value = Object.values({ name: httpQuery.value.name, phone: httpQuery.value.phone }).some((value) => value.length > 0);
    submit();
}, { deep: true });

const clearFilters = () => {
    httpQuery.value = {
        name: "",
        phone: ""
    };
    submit();
};
</script>