<template>
    <CoelhoCard class="bg-white my-4" size="full">
        <form @submit.prevent="submit()">
            <div class="grid grid-cols-1 gap-x-10 md:grid-cols-2 lg:grid-cols-3 lg:place-items-start xl:grid-cols-4 gap-4"
                id="automResourcesAll">
                <CoelhoInput v-model="servicesFilters.name" label="Nome" placeholder="Nome do recurso"
                    :leftIcon="ChatBubbleLeftEllipsisIcon" />
                <CoelhoRange v-model="servicesFilters.price" label="Preço" suffix="€" />
                <CoelhoRange v-model="servicesFilters.duration" label="Duração" suffix="min" />
            </div>
            <div class="mt-4 flex gap-4 justify-end">
                <CoelhoButton v-if="hasFilters" variant="danger" :icon="XCircleIcon" @click="clearFilters">
                    Apagar filtros
                </CoelhoButton>
                <CoelhoButton type="submit" variant="primary" :loading="loading" :icon="MagnifyingGlassIcon">
                    Procurar
                </CoelhoButton>
            </div>
        </form>
    </CoelhoCard>
</template>

<script setup lang="ts">
import { ref, onMounted, watch, computed } from "vue";
import { storeToRefs } from "pinia";

import { CoelhoButton, CoelhoCard, CoelhoInput, CoelhoRange } from "@/components";

import { engineQueries } from "@/composables/engineQueries";
import { filtersServices } from "../$composables/query";

import { useServicesStore } from "@/stores/services";

import { ChatBubbleLeftEllipsisIcon, MagnifyingGlassIcon, XCircleIcon } from "@heroicons/vue/24/solid";

const { formatForRouter, formatFromRouter } = engineQueries();

const servicesStore = useServicesStore();

const { getterQuery } = storeToRefs(servicesStore);

const emits = defineEmits(["submit"]);
defineProps({ loading: Boolean });

interface RangeFilter {
    min: number;
    max: number;
}

interface ServicesFilters {
    name: string;
    price: RangeFilter;
    duration: RangeFilter;
}

interface ServicesQuery {
    name: string;
    minPrice: number;
    maxPrice: number;
    minDuration: number;
    maxDuration: number;
}

const servicesFilters = ref<ServicesFilters>({
    name: "",
    price: {
        min: 0,
        max: 5000
    },
    duration: {
        min: 0,
        max: 360
    }
});

const httpQuery = computed<ServicesQuery>(() => {
    return {
        name: servicesFilters.value.name,
        minPrice: servicesFilters.value.price.min,
        maxPrice: servicesFilters.value.price.max,
        minDuration: servicesFilters.value.duration.min,
        maxDuration: servicesFilters.value.duration.max
    };
});

const hasFilters = ref(false);

const submit = () => {
    emits("submit", formatForRouter({ ...httpQuery.value, page: 1 }));
};

const setQueries = () => {
    if (getterQuery.value) {
        const routerFilters = { ...formatFromRouter(getterQuery.value, filtersServices.value) }
        servicesFilters.value = {
            ...httpQuery.value,
            price: {
                min: routerFilters.minPrice,
                max: routerFilters.maxPrice
            },
            duration: {
                min: routerFilters.minDuration,
                max: routerFilters.maxDuration
            },
            ...routerFilters
        };
    }
};

onMounted(async () => {
    setQueries();
})

watch(servicesFilters, () => {
    const hasPriceFilters = servicesFilters.value.price.min > 0 || servicesFilters.value.price.max < 5000;
    const hasDurationFilters = servicesFilters.value.duration.min > 0 || servicesFilters.value.duration.max < 360;
    const hasNameFilter = servicesFilters.value.name !== "";
    hasFilters.value = hasPriceFilters || hasDurationFilters || hasNameFilter;
}, { deep: true });

const clearFilters = () => {
    servicesFilters.value = {
        name: "",
        price: {
            min: 0,
            max: 5000
        },
        duration: {
            min: 0,
            max: 360
        }
    };
    submit();
};
</script>