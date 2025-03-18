<template>
  <component :is="resolvedComponent" v-bind="linkProps" :class="[
    'inline-flex items-center transition-colors',
    'hover:underline focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-primary',
    sizeClass,
    colorClass,
    weightClass,
    { 'cursor-not-allowed opacity-50': disabled }
  ]">
    <slot />
    <CoelhoIcon v-if="isExternal" :icon="ArrowTopRightOnSquareIcon" class="ml-1" :size="iconSize" />
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
  color?: string;
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

const sizeClass = computed(() => `text-${props.size}`);
const colorClass = computed(() => props.color.startsWith('text-') ? props.color : `text-${props.color}`);
const weightClass = computed(() => {
  const weights = {
    normal: 'font-normal',
    medium: 'font-medium',
    semibold: 'font-semibold',
    bold: 'font-bold',
  };
  return weights[props.weight];
});

const iconSize = computed(() => {
  const sizes: Record<NonNullable<Props['size']>, 'xs' | 'sm'> = {
    xs: 'xs',
    sm: 'xs',
    base: 'sm',
    lg: 'sm',
  };
  return sizes[props.size];
});
</script>
