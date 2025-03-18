<template>
  <span :class="[
    sizeClass,
    colorClass,
    weightClass,
    {
      'italic': italic,
      'underline': underline,
      'line-through': lineThrough,
      'uppercase': uppercase,
    }
  ]">
    <slot />
  </span>
</template>

<script setup lang="ts">
import { computed } from 'vue';

interface Props {
  size?: 'xs' | 'sm' | 'base' | 'lg';
  color?: string;
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
</script>
