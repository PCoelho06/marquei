<template>
  <component :is="tag" :class="{
    'font-bold leading-tight': true,
    'text-4xl': props.level === 1,
    'text-3xl': props.level === 2,
    'text-2xl': props.level === 3,
    'text-xl': props.level === 4,
    'text-lg': props.level === 5,
    'text-base': props.level === 6,
    'text-primary': props.color === 'primary',
    'text-gray-500': props.color === 'secondary',
    'text-green-500': props.color === 'success',
    'text-yellow-500': props.color === 'warning',
    'text-red-500': props.color === 'danger',
    'text-black': props.color === 'dark',
    'text-white': props.color === 'light',
    'mb-4': withMargin
  }">
    <slot />
  </component>
</template>

<script setup lang="ts">
import { computed } from 'vue';

interface Props {
  level?: 1 | 2 | 3 | 4 | 5 | 6;
  size?: 'xs' | 'sm' | 'md' | 'lg' | 'xl' | '2xl' | '3xl' | '4xl';
  color?: 'primary' | 'secondary' | 'success' | 'warning' | 'danger' | 'dark' | 'light';
  withMargin?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  level: 1,
  color: 'dark',
  withMargin: true,
});

const tag = computed(() => `h${props.level}`);
</script>
