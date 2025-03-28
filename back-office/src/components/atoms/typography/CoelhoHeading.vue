<template>
  <component :is="tag" :class="{
    'font-bold leading-tight': true,
    'font-brand tracking-tighter': logo,
    'text-7xl': size === 'xl',
    'text-5xl': size === 'lg',
    'text-3xl': size === 'md',
    'text-2xl': size === 'sm',
    'text-xl': size === 'xs',
    'text-primary': color === 'primary',
    'text-gray-500': color === 'secondary',
    'text-green-500': color === 'success',
    'text-yellow-500': color === 'warning',
    'text-red-500': color === 'danger',
    'text-black': color === 'dark',
    'text-white': color === 'light',
    'mb-4': withMargin
  }">
    <slot />
  </component>
</template>

<script setup lang="ts">
import { computed } from 'vue';

interface Props {
  level?: 1 | 2 | 3 | 4 | 5 | 6;
  size?: 'xs' | 'sm' | 'md' | 'lg' | 'xl';
  color?: 'primary' | 'secondary' | 'success' | 'warning' | 'danger' | 'dark' | 'light';
  logo?: boolean;
  withMargin?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  level: 1,
  color: 'dark',
  withMargin: true,
  logo: false,
});

const tag = computed(() => `h${props.level}`);
</script>
