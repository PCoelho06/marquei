<template>
  <CoelhoCard class="bg-white my-4" size="full">
    <form @submit.prevent="submit()">
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 lg:place-items-start gap-4" id="automResourcesAll">
        <CoelhoSelect v-model="httpQuery.salon" :leftIcon="BuildingStorefrontIcon" label="Salão" component="select"
          :multiple=true :options="salonOptions" class="w-full" />
        <CoelhoSelect v-model="httpQuery.type" :leftIcon="TagIcon" label="Tipo" component="select" :multiple=true
          :options="resourceTypesOptions" class="w-full" />
        <CoelhoInput v-model="httpQuery.name" label="Nome" placeholder="Nome do recurso"
          :leftIcon="ChatBubbleLeftEllipsisIcon" class="w-full" />
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
import { ref, onMounted, watch } from "vue";
import { storeToRefs } from "pinia";

import { CoelhoSelect, CoelhoButton, CoelhoCard, CoelhoInput } from "@/components";

import { mappers } from "@/utils";
import { engineQueries } from "@/composables/engineQueries";
import { resourceTypesOptions } from '@/composables/constants';
import { filtersResources } from "../$composables/query";

import { useResourcesStore } from "@/stores/resources";
import { useSalonsStore } from "@/stores/salons";

import type { ResourceFilters } from "@/types/resources";
import type { SelectOption } from "@/types";
import { BuildingStorefrontIcon, ChatBubbleLeftEllipsisIcon, MagnifyingGlassIcon, TagIcon, XCircleIcon } from "@heroicons/vue/24/solid";

const { formatForRouter, formatFromRouter } = engineQueries();

const resourcesStore = useResourcesStore();
const salonsStore = useSalonsStore();

const { getterQuery } = storeToRefs(resourcesStore);

const emits = defineEmits(["submit"]);
defineProps({ loading: Boolean });

const httpQuery = ref<ResourceFilters>({
  salon: [],
  type: [],
  name: ""
});
const salonOptions = ref<SelectOption[]>([]);
const hasFilters = ref(false);

const submit = () => {
  emits("submit", formatForRouter({ ...httpQuery.value, page: 1 }));
};

const setQueries = () => {
  if (getterQuery.value) {
    httpQuery.value = {
      ...httpQuery.value,
      ...formatFromRouter(getterQuery.value, filtersResources.value)
    };
  }
};

onMounted(async () => {
  await salonsStore.searchSalons({ httpQuery: {} });
  if (salonsStore.getterSalonList && salonsStore.getterSalonList.length > 0) {
    salonOptions.value = mappers.mapSalonsToOptions(salonsStore.getterSalonList);
  }
  setQueries();
})

watch(httpQuery, () => {
  hasFilters.value = Object.values({ salon: httpQuery.value.salon, type: httpQuery.value.type, name: httpQuery.value.name }).some((value) => value.length > 0);
}, { deep: true });

const clearFilters = () => {
  httpQuery.value = {
    salon: [],
    type: [],
    name: ""
  };
  submit();
};
</script>