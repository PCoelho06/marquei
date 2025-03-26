<template>
  <p :class="{
    'text-xs': size === 'xs',
    'text-sm': size === 'sm',
    'text-base': size === 'base',
    'text-lg': size === 'lg',
    'text-primary': color === 'primary',
    'text-stroke': color === 'secondary',
    'text-green-500': color === 'success',
    'text-yellow-500': color === 'warning',
    'text-red-500': color === 'danger',
    'text-black': color === 'dark',
    'text-white': color === 'light',
    'font-normal': weight === 'normal',
    'font-medium': weight === 'medium',
    'font-semibold': weight === 'semibold',
    'font-bold': weight === 'bold',
    'text-left': align === 'left',
    'text-center': align === 'center',
    'text-right': align === 'right',
    'text-justify': align === 'justify',
    'mb-4': withMargin
  }">
    <slot />
  </p>
</template>

<script setup lang="ts">
import { computed } from 'vue';

interface Props {
  size?: 'xs' | 'sm' | 'base' | 'lg';
  color?: 'primary' | 'secondary' | 'success' | 'warning' | 'danger' | 'dark' | 'light';
  align?: 'left' | 'center' | 'right' | 'justify';
  weight?: 'normal' | 'medium' | 'semibold' | 'bold';
  withMargin?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  size: 'base',
  color: 'dark',
  align: 'left',
  weight: 'normal',
  withMargin: true,
});

const sizeClass = computed(() => `text-${props.size}`);
const colorClass = computed(() => props.color.startsWith('text-') ? props.color : `text-${props.color}`);
const alignClass = computed(() => `text-${props.align}`);
const weightClass = computed(() => {
  const weights = {
    normal: 'font-normal',
    medium: 'font-medium',
    semibold: 'font-semibold',
    bold: 'font-bold',
  };
  return weights[props.weight];
});
</script>
