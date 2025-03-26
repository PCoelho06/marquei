<template>
  <component :is="resolvedComponent" v-bind="linkProps" :class="{
    'inline-flex items-center transition-colors': true,
    'hover:underline focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary': true,
    'text-xs': size === 'xs',
    'text-sm': size === 'sm',
    'text-base': size === 'base',
    'text-lg': size === 'lg',
    'text-primary': color === 'primary',
    'text-gray-500': color === 'secondary',
    'text-green-500': color === 'success',
    'text-yellow-500': color === 'warning',
    'text-red-500': color === 'danger',
    'text-black': color === 'dark',
    'text-white': color === 'light',
    'font-normal': weight === 'normal',
    'font-medium': weight === 'medium',
    'font-semibold': weight === 'semibold',
    'font-bold': weight === 'bold',
    'cursor-not-allowed opacity-50': disabled
  }">
    <slot />
    <CoelhoIcon v-if="isExternal" :icon="ArrowTopRightOnSquareIcon" class="ml-1"
      :size="size === 'xs' || size === 'sm' ? 'xs' : 'sm'" />
  </component>
</template>

<script setup lang="ts">
import { computed, inject } from 'vue';
import type { Router } from 'vue-router';
import { ArrowTopRightOnSquareIcon } from '@heroicons/vue/24/solid';
import CoelhoIcon from '../CoelhoIcon.vue';

interface Props {
  href?: string;
  to?: string;
  size?: 'xs' | 'sm' | 'base' | 'lg';
  color?: 'primary' | 'secondary' | 'success' | 'warning' | 'danger' | 'dark' | 'light';
  weight?: 'normal' | 'medium' | 'semibold' | 'bold';
  disabled?: boolean;
  target?: string;
}

const props = withDefaults(defineProps<Props>(), {
  size: 'base',
  color: 'primary',
  weight: 'normal',
  disabled: false,
});

const router = inject('router') as Router | undefined;

const isExternal = computed(() => {
  if (!props.href) return false;
  return props.href.startsWith('http') || props.href.startsWith('//');
});

const resolvedComponent = computed(() => {
  if (!router || !props.to) return 'a';
  return 'router-link';
});

const linkProps = computed(() => {
  if (props.disabled) return {};

  if (props.to) {
    if (!router) {
      return { href: props.to };
    }
    return { to: props.to };
  }

  if (isExternal.value) {
    return {
      href: props.href,
      target: props.target || '_blank',
      rel: 'noopener noreferrer'
    };
  }

  return { href: props.href };
});
</script>
