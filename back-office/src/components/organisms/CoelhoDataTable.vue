<template>
  <div class="space-y-4">
    <div class="flex justify-between items-center">
      <div class="flex space-x-2">
        <slot name="actions" />
      </div>

      <CoelhoSelect v-if="totalElements > 10" v-model="limit" label="Limite" :options="limits" class="w-36">
        <template #default="{ item }">
          <span>{{ item.name }}</span>
        </template>
      </CoelhoSelect>
    </div>

    <div class="text-sm text-gray-500">
      <template v-if="totalPages > 1">
        <span class="font-medium">{{ first }}</span>
        <span> a </span>
        <span class="font-medium">{{ last }}</span>
        <span> de </span>
      </template>
      <span class="font-medium">{{ totalElements }}</span>
      <span v-if="totalElements > 1"> resultados</span>
      <span v-else> resultado</span>
    </div>
    <div class="overflow-x-auto border border-stroke rounded-lg">
      <table class="min-w-full divide-y divide-stroke">
        <thead class="bg-whitten">
          <tr>
            <th v-if="selectable" scope="col" class="w-12 px-3 py-3.5">
              <CoelhoCheckbox :model-value="isAllSelected" :indeterminate="hasSelection && !isAllSelected"
                @change="toggleAll" />
            </th>

            <th v-for="column in columns" :key="column.key" scope="col"
              class="px-3 py-3.5 text-left text-sm font-medium text-dark" :class="[
                column.sortable ? 'cursor-pointer select-none' : '',
                column.width ? `w-${column.width}` : '',
              ]" @click="sortColumn(column.key)">
              <div class="flex items-center space-x-1">
                <span>{{ column.label }}</span>
                <template v-if="column.sortable">
                  <ChevronUpIcon v-if="sortKey === column.key && sortOrder === 'asc'" class="w-4 h-4 text-primary" />
                  <ChevronDownIcon v-else-if="sortKey === column.key && sortOrder === 'desc'"
                    class="w-4 h-4 text-primary" />
                  <ChevronUpDownIcon v-else class="w-4 h-4 text-gray-400" />
                </template>
              </div>
            </th>

            <!-- Colonne des actions -->
            <th v-if="$slots.rowActions" scope="col" class="w-24 px-3 py-3.5" />
          </tr>
        </thead>

        <tbody class="divide-y divide-stroke bg-white">
          <tr v-if="items.length === 0" class="text-center">
            <td :colspan="computedColSpan" class="px-3 py-4 text-sm text-gray-500">
              Aucun résultat trouvé
            </td>
          </tr>

          <tr v-for="item in items" :key="getItemKey(item)" :class="[
            'hover:bg-gray-50 transition-colors',
            { 'bg-blue-50/50': isSelected(item) }
          ]">
            <!-- Checkbox de sélection -->
            <td v-if="selectable" class="w-12 px-3 py-4">
              <CoelhoCheckbox :model-value="isSelected(item)" @change="toggleSelection(item)" />
            </td>

            <!-- Cellules de données -->
            <td v-for="column in columns" :key="column.key" class="whitespace-nowrap px-3 py-4 text-sm text-gray-900">
              <slot :name="`cell-${column.key}`" :item="item" :value="item[column.key]">
                {{ formatValue(item[column.key], column.format) }}
              </slot>
            </td>

            <!-- Actions par ligne -->
            <td v-if="$slots.rowActions" class="whitespace-nowrap px-3 py-4 text-sm text-gray-900">
              <slot name="rowActions" :item="item" />
            </td>
          </tr>
        </tbody>
      </table>
    </div>

    <!-- Pagination -->
    <div v-if="paginated && totalPages > 1" class="flex justify-center items-center">
      <CoelhoPagination v-model:currentPage="currentPage" :total-pages="props.totalPages" :total-items="items.length"
        :items-per-page="limit" />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch, useSlots } from 'vue';
import { useRoute } from 'vue-router';
import {
  ChevronUpIcon,
  ChevronDownIcon,
  ChevronUpDownIcon,
} from '@heroicons/vue/24/solid';
import CoelhoCheckbox from '../atoms/CoelhoCheckbox.vue';
import CoelhoPagination from '../molecules/CoelhoPagination.vue';
import CoelhoSelect from '../atoms/CoelhoSelect.vue';
import type { SelectOption } from '../types/tables';
import type { Item } from '@/types';

interface Column {
  key: string;
  label: string;
  sortable?: boolean;
  width?: string;
  format?: (value: string | number) => string;
}

interface Props {
  items: Item[];
  columns: Column[];
  itemKey?: string;
  searchable?: boolean;
  selectable?: boolean;
  paginated?: boolean;
  totalElements?: number;
  first?: number;
  last?: number;
  totalPages?: number;
}

const route = useRoute();

const props = withDefaults(defineProps<Props>(), {
  itemKey: 'id',
  searchable: true,
  selectable: false,
  paginated: true,
  totalElements: 10,
  first: 1,
  last: 10,
  totalPages: 1,
});

const emit = defineEmits<{
  (e: 'update:selected', items: object[]): void;
  (e: 'update:limit', limit: number): void;
  (e: 'update:page', page: number): void;
  (e: 'update:sort', sort: { key: string, order: 'asc' | 'desc' }): void;
  (e: 'search', query: string): void;
}>();

const slots = useSlots()
const sort = route.query.sort ? route.query.sort.toString().split(',') : [];

// État local
const selectedItems = ref<Item[]>([]);
const currentPage = ref(route.query.page ? Number(route.query.page) : 1);
const limit = ref(route.query.limit ? Number(route.query.limit) : 10);
const sortKey = ref(sort[0] ? sort[0] : '');
const sortOrder = ref<'asc' | 'desc'>(sort[1] === 'asc' || sort[1] === 'desc' ? sort[1] : 'asc');

const limits: SelectOption[] = [
  { label: '10', value: 10 },
  { label: '25', value: 25 },
  { label: '50', value: 50 },
  { label: '100', value: 100 },
];

const computedColSpan = computed(() => {
  let count = props.columns.length;
  if (props.selectable) count++;
  if (slots.rowActions) count++;
  return count;
});

// Sélection
const isAllSelected = computed(() => {
  return props.items.length > 0 &&
    props.items.every(item => isSelected(item));
});

const hasSelection = computed(() => selectedItems.value.length > 0);

const getItemKey = (item: Item) => {
  return item[props.itemKey];
};

const isSelected = (item: Item) => {
  return selectedItems.value.some(
    selected => getItemKey(selected) === getItemKey(item)
  );
};

const toggleSelection = (item: Item) => {
  const index = selectedItems.value.findIndex(
    selected => getItemKey(selected) === getItemKey(item)
  );

  if (index === -1) {
    selectedItems.value.push(item);
  } else {
    selectedItems.value.splice(index, 1);
  }

  emit('update:selected', selectedItems.value);
};

const toggleAll = () => {
  if (isAllSelected.value) {
    selectedItems.value = [];
  } else {
    selectedItems.value = [...props.items];
  }
  emit('update:selected', selectedItems.value);
};

// Formatage des valeurs
const formatValue = (value: string | number, format?: (value: string | number) => string) => {
  if (format) return format(value);
  return value;
};

watch(limit, (newLimit) => {
  currentPage.value = 1;
  emit('update:limit', newLimit);
});

watch(currentPage, (newPage) => {
  emit('update:page', newPage);
});

const sortColumn = (key: string) => {
  if (sortKey.value === key) {
    sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortKey.value = key;
    sortOrder.value = 'asc';
  }

  emit('update:sort', { key: sortKey.value, order: sortOrder.value });
};
</script>
