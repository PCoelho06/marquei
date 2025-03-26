<template>
  <span :class="{
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
    'italic': italic,
    'underline': underline,
    'line-through': lineThrough,
    'uppercase': uppercase,
  }">
    <slot />
  </span>
</template>

<script setup lang="ts">
import { computed } from 'vue';

interface Props {
  size?: 'xs' | 'sm' | 'base' | 'lg';
  color?: 'primary' | 'secondary' | 'success' | 'warning' | 'danger' | 'dark' | 'light';
  weight?: 'normal' | 'medium' | 'semibold' | 'bold';
  italic?: boolean;
  underline?: boolean;
  lineThrough?: boolean;
  uppercase?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  size: 'base',
  color: 'dark',
  weight: 'normal',
  italic: false,
  underline: false,
  lineThrough: false,
  uppercase: false,
});
</script>
