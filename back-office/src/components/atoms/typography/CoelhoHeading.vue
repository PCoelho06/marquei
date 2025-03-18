<template>
  <component :is="tag" :class="[
    'font-bold leading-tight',
    sizeClasses,
    colorClass,
    { 'mb-4': withMargin }
  ]">
    <slot />
  </component>
</template>

<script setup lang="ts">
import { computed } from 'vue';

interface Props {
  level?: 1 | 2 | 3 | 4 | 5 | 6;
  size?: 'xs' | 'sm' | 'md' | 'lg' | 'xl' | '2xl' | '3xl' | '4xl';
  color?: string;
  withMargin?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  level: 1,
  color: 'dark',
  withMargin: true,
});

const tag = computed(() => `h${props.level}`);

const sizeClasses = computed(() => {
  const defaultSizes = {
    1: 'text-4xl',
    2: 'text-3xl',
    3: 'text-2xl',
    4: 'text-xl',
    5: 'text-lg',
    6: 'text-base',
  };

  if (props.size) {
    return `text-${props.size}`;
  }

  return defaultSizes[props.level];
});

const colorClass = computed(() => {
  if (props.color.startsWith('text-')) return props.color;
  return `text-${props.color}`;
});
</script>
