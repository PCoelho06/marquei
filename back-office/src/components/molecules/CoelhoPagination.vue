<template>
  <nav class="flex items-center justify-between" role="navigation" aria-label="Pagination">
    <!-- Version Mobile -->
    <div class="flex flex-1 justify-between sm:hidden">
      <CoelhoButton variant="secondary" size="sm" :disabled="currentPage === 1"
        @click="handlePageChange(currentPage - 1)">
        Anterior
      </CoelhoButton>
      <CoelhoButton variant="secondary" size="sm" :disabled="currentPage === totalPages"
        @click="handlePageChange(currentPage + 1)">
        Seguinte
      </CoelhoButton>
    </div>

    <div class="hidden sm:flex sm:items-center sm:space-x-1">
      <CoelhoButton variant="secondary" size="sm" :disabled="currentPage === 1"
        @click="handlePageChange(currentPage - 1)">
        <ChevronLeftIcon class="h-4 w-4" />
        <span class="ml-1">Anterior</span>
      </CoelhoButton>

      <div class="flex items-center space-x-1">
        <button v-if="shouldShowFirst" type="button" :class="[
          'px-3 py-1 text-sm rounded-md',
          currentPage === 1 ? 'bg-primary text-white' : 'text-gray-500 hover:bg-gray-100',
        ]
          " @click="handlePageChange(1)">
          1
        </button>

        <span v-if="shouldShowFirstEllipsis" class="px-2 text-gray-500">...</span>

        <button v-for="page in visiblePages" :key="page" type="button" :class="[
          'px-3 py-1 text-sm rounded-md',
          page === currentPage ? 'bg-primary text-white' : 'text-gray-500 hover:bg-gray-100',
        ]
          " @click="handlePageChange(page)">
          {{ page }}
        </button>

        <span v-if="shouldShowLastEllipsis" class="px-2 text-gray-500">...</span>

        <button v-if="shouldShowLast" type="button" :class="[
          'px-3 py-1 text-sm rounded-md',
          currentPage === totalPages ? 'bg-primary text-white' : 'text-gray-500 hover:bg-gray-100',
        ]
          " @click="handlePageChange(totalPages)">
          {{ totalPages }}
        </button>
      </div>

      <!-- Bouton Suivant -->
      <CoelhoButton variant="secondary" size="sm" :disabled="currentPage === totalPages"
        @click="handlePageChange(currentPage + 1)">
        <span class="mr-1">Seguinte</span>
        <ChevronRightIcon class="h-4 w-4" />
      </CoelhoButton>
    </div>
  </nav>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/24/solid';
import CoelhoButton from '../atoms/CoelhoButton.vue';

interface Props {
  currentPage: number;
  totalPages: number;
  totalItems?: number;
  itemsPerPage?: number;
  maxVisiblePages?: number;
  showInfo?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  maxVisiblePages: 5,
  showInfo: true,
});

const emit = defineEmits<{
  (e: 'update:currentPage', page: number): void;
  (e: 'change', page: number): void;
}>();

// Calcul des éléments affichés
const startItem = computed(() => {
  if (!props.itemsPerPage) return undefined;
  return (props.currentPage - 1) * props.itemsPerPage + 1;
});

const endItem = computed(() => {
  if (!props.itemsPerPage || !props.totalItems) return undefined;
  return Math.min(props.currentPage * props.itemsPerPage, props.totalItems);
});

// Logique d'affichage des pages
const visiblePages = computed(() => {
  const halfVisible = Math.floor(props.maxVisiblePages / 2);
  let start = Math.max(props.currentPage - halfVisible, 1);
  const end = Math.min(start + props.maxVisiblePages - 1, props.totalPages);

  if (end - start + 1 < props.maxVisiblePages) {
    start = Math.max(end - props.maxVisiblePages + 1, 1);
  }

  return Array.from(
    { length: end - start + 1 },
    (_, i) => start + i
  );
});

// Conditions d'affichage des ellipsis et des extrémités
const shouldShowFirst = computed(() => !visiblePages.value.includes(1));
const shouldShowLast = computed(() => !visiblePages.value.includes(props.totalPages));
const shouldShowFirstEllipsis = computed(() => {
  return shouldShowFirst.value && visiblePages.value[0] > 2;
});
const shouldShowLastEllipsis = computed(() => {
  return shouldShowLast.value && visiblePages.value[visiblePages.value.length - 1] < props.totalPages - 1;
});

// Gestion du changement de page
const handlePageChange = (page: number) => {
  if (page < 1 || page > props.totalPages) return;
  emit('update:currentPage', page);
};
</script>
