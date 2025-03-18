<template>
  <nav aria-label="Fil d'ariane">
    <ol class="flex items-center space-x-2">
      <!-- Home -->
      <li v-if="showHome" class="flex items-center">
        <CoelhoLink :to="homePath" class="text-gray-500 hover:text-primary" :class="{ 'flex items-center': homeIcon }">
          <CoelhoIcon v-if="homeIcon" :icon="homeIcon" size="sm" class="mr-1" />
          {{ homeLabel }}
        </CoelhoLink>
      </li>

      <!-- Séparateur après Home -->
      <li v-if="showHome && items.length > 0" class="flex items-center" aria-hidden="true">
        <component :is="separatorIcon ? CoelhoIcon : 'span'"
          v-bind="separatorIcon ? { icon: separatorIcon, size: 'sm' } : {}" class="text-gray-400">
          {{ separatorIcon ? '' : separator }}
        </component>
      </li>

      <!-- Items -->
      <template v-for="(item, index) in items" :key="index">
        <li class="flex items-center">
          <CoelhoLink v-if="item.to && !isLast(index)" :to="item.to" class="text-gray-500 hover:text-primary"
            :class="{ 'flex items-center': item.icon }">
            <CoelhoIcon v-if="item.icon" :icon="item.icon" size="sm" class="mr-1" />
            {{ item.label }}
          </CoelhoLink>
          <span v-else class="text-gray-900" :class="{ 'flex items-center': item.icon }">
            <CoelhoIcon v-if="item.icon" :icon="item.icon" size="sm" class="mr-1" />
            {{ item.label }}
          </span>
        </li>

        <!-- Séparateur -->
        <li v-if="!isLast(index)" class="flex items-center" aria-hidden="true">
          <component :is="separatorIcon ? CoelhoIcon : 'span'"
            v-bind="separatorIcon ? { icon: separatorIcon, size: 'sm' } : {}" class="text-gray-400">
            {{ separatorIcon ? '' : separator }}
          </component>
        </li>
      </template>
    </ol>
  </nav>
</template>

<script setup lang="ts">
import type { Component } from 'vue';
import { HomeIcon, ChevronRightIcon } from '@heroicons/vue/24/solid';
import CoelhoIcon from '../atoms/CoelhoIcon.vue';
import CoelhoLink from '../atoms/typography/CoelhoLink.vue';

interface BreadcrumbItem {
  label: string;
  to?: string;
  icon?: Component;
}

interface Props {
  items: BreadcrumbItem[];
  showHome?: boolean;
  homeLabel?: string;
  homePath?: string;
  homeIcon?: Component;
  separator?: string;
  separatorIcon?: Component;
}

const props = withDefaults(defineProps<Props>(), {
  showHome: true,
  homeLabel: 'Accueil',
  homePath: '/',
  homeIcon: HomeIcon,
  separator: '/',
  separatorIcon: ChevronRightIcon,
});

const isLast = (index: number) => {
  return index === props.items.length - 1;
};
</script>
