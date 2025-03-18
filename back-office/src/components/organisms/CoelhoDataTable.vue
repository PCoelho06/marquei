<template>
  <div class="space-y-4">
    <!-- Header avec recherche et actions -->
    <div class="flex justify-between items-center">
      <!-- Recherche -->
      <CoelhoInputGroup v-if="searchable" v-model="searchQuery" placeholder="Rechercher..."
        :prefix="MagnifyingGlassIcon" class="w-64" />

      <!-- Actions globales -->
      <div class="flex space-x-2">
        <slot name="actions" />
      </div>
    </div>

    <!-- Tableau -->
    <div class="overflow-x-auto border border-stroke rounded-lg">
      <table class="min-w-full divide-y divide-stroke">
        <thead class="bg-whitten">
          <tr>
            <!-- Checkbox de sélection -->
            <th v-if="selectable" scope="col" class="w-12 px-3 py-3.5">
              <CoelhoCheckbox :model-value="isAllSelected" :indeterminate="hasSelection && !isAllSelected"
                @change="toggleAll" />
            </th>

            <!-- En-têtes des colonnes -->
            <th v-for="column in columns" :key="column.key" scope="col"
              class="px-3 py-3.5 text-left text-sm font-medium text-dark" :class="[
                column.sortable ? 'cursor-pointer select-none' : '',
                column.width ? `w-${column.width}` : '',
              ]" @click="column.sortable && sort(column.key)">
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
          <tr v-if="filteredItems.length === 0" class="text-center">
            <td :colspan="computedColSpan" class="px-3 py-4 text-sm text-gray-500">
              Aucun résultat trouvé
            </td>
          </tr>

          <tr v-for="item in paginatedItems" :key="getItemKey(item)" :class="[
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
    <div v-if="paginated" class="flex justify-between items-center">
      <div class="text-sm text-gray-500">
        {{ paginationInfo }}
      </div>
      <CoelhoPagination v-model:currentPage="currentPage" :total-pages="totalPages" :total-items="filteredItems.length"
        :items-per-page="itemsPerPage" />
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch, useSlots } from 'vue';
import {
  ChevronUpIcon,
  ChevronDownIcon,
  ChevronUpDownIcon,
  MagnifyingGlassIcon,
} from '@heroicons/vue/24/solid';
import CoelhoInputGroup from '../molecules/CoelhoInputGroup.vue';
import CoelhoCheckbox from '../atoms/CoelhoCheckbox.vue';
import CoelhoPagination from '../molecules/CoelhoPagination.vue';

interface Column {
  key: string;
  label: string;
  sortable?: boolean;
  width?: string;
  format?: (value: string | number) => string;
}

interface Item {
  [key: string]: string | number;
}

interface Props {
  items: Item[];
  columns: Column[];
  itemKey?: string;
  searchable?: boolean;
  selectable?: boolean;
  paginated?: boolean;
  itemsPerPage?: number;
}

const props = withDefaults(defineProps<Props>(), {
  itemKey: 'id',
  searchable: true,
  selectable: false,
  paginated: true,
  itemsPerPage: 10,
});

const emit = defineEmits<{
  (e: 'update:selected', items: object[]): void;
  (e: 'sort', key: string, order: 'asc' | 'desc'): void;
  (e: 'search', query: string): void;
}>();

const slots = useSlots()

// État local
const searchQuery = ref('');
const selectedItems = ref<Item[]>([]);
const currentPage = ref(1);
const sortKey = ref('');
const sortOrder = ref<'asc' | 'desc'>('asc');

// Calcul du nombre de colonnes
const computedColSpan = computed(() => {
  let count = props.columns.length;
  if (props.selectable) count++;
  if (slots.rowActions) count++;
  return count;
});

// Filtrage
const filteredItems = computed(() => {
  let result = [...props.items];

  // Recherche
  if (searchQuery.value) {
    const query = searchQuery.value.toLowerCase();
    result = result.filter(item =>
      props.columns.some(column => {
        const value = item[column.key];
        return value && String(value).toLowerCase().includes(query);
      })
    );
  }

  // Tri
  if (sortKey.value) {
    result.sort((a, b) => {
      const aVal = a[sortKey.value];
      const bVal = b[sortKey.value];
      const modifier = sortOrder.value === 'asc' ? 1 : -1;

      if (aVal < bVal) return -1 * modifier;
      if (aVal > bVal) return 1 * modifier;
      return 0;
    });
  }

  return result;
});

// Pagination
const paginatedItems = computed(() => {
  if (!props.paginated) return filteredItems.value;

  const start = (currentPage.value - 1) * props.itemsPerPage;
  const end = start + props.itemsPerPage;
  return filteredItems.value.slice(start, end);
});

const totalPages = computed(() => {
  return Math.ceil(filteredItems.value.length / props.itemsPerPage);
});

const paginationInfo = computed(() => {
  const start = (currentPage.value - 1) * props.itemsPerPage + 1;
  const end = Math.min(start + props.itemsPerPage - 1, filteredItems.value.length);
  return `${start}-${end} sur ${filteredItems.value.length}`;
});

// Sélection
const isAllSelected = computed(() => {
  return filteredItems.value.length > 0 &&
    filteredItems.value.every(item => isSelected(item));
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
    selectedItems.value = [...filteredItems.value];
  }
  emit('update:selected', selectedItems.value);
};

// Tri
const sort = (key: string) => {
  if (sortKey.value === key) {
    sortOrder.value = sortOrder.value === 'asc' ? 'desc' : 'asc';
  } else {
    sortKey.value = key;
    sortOrder.value = 'asc';
  }
  emit('sort', key, sortOrder.value);
};

// Formatage des valeurs
const formatValue = (value: string | number, format?: (value: string | number) => string) => {
  if (format) return format(value);
  return value;
};

// Watch pour la recherche
watch(searchQuery, (newQuery) => {
  currentPage.value = 1;
  emit('search', newQuery);
});
</script>
