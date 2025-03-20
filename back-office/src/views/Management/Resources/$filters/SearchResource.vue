<template>
  <CoelhoCard class="bg-white my-4">
    <form @submit.prevent="submit()">
      <div class="grid grid-cols-1 gap-x-10 md:grid-cols-2 lg:grid-cols-3 lg:place-items-start xl:grid-cols-4"
        id="automResourcesAll">
        <CoelhoInputGroup v-model="httpQuery.salon" label="Salão" component="select" :multiple=true
          :options="salonOptions" />
        <CoelhoInputGroup v-model="httpQuery.type" label="Tipo" component="select" :multiple=true
          :options="resourceTypesOptions" />
        <CoelhoInputGroup v-model="httpQuery.name" label="Nome" placeholder="Nome do recurso" />
      </div>
      <div class="mt-4 flex justify-end">
        <CoelhoButton type="submit" variant="primary" :loading="loading">
          Procurar
        </CoelhoButton>
      </div>
    </form>
  </CoelhoCard>
</template>

<script setup lang="ts">
import { ref, onMounted } from "vue";
import { storeToRefs } from "pinia";

import { CoelhoInputGroup, CoelhoButton, CoelhoCard } from "@/components";

import { mappers } from "@/utils";
import { engineQueries } from "@/composables/engineQueries";
import { resourceTypesOptions } from '@/composables/constants';
import { filtersResources } from "../$composables/query";

import { useResourcesStore } from "@/stores/resources";
import { useSalonsStore } from "@/stores/salons";

import type { ResourceFilters } from "@/types/resources";
import type { SelectOption } from "@/types";

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
const salonOptions = ref<SelectOption[]>();

const submit = () => {
  const query = formatForRouter({ ...httpQuery.value, page: 1 });
  emits("submit", query);
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
  await salonsStore.listSalons();
  if (salonsStore.getterSalons && salonsStore.getterSalons.length > 0) {
    salonOptions.value = mappers.mapSalonsToOptions(salonsStore.getterSalons);
  }
  setQueries();
})
</script>