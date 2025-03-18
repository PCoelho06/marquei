<template>
  <p :class="[
    sizeClass,
    colorClass,
    alignClass,
    weightClass,
    { 'mb-4': withMargin }
  ]">
    <slot />
  </p>
</template>

<script setup lang="ts">
import { computed } from 'vue';

interface Props {
  size?: 'xs' | 'sm' | 'base' | 'lg';
  color?: string;
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
