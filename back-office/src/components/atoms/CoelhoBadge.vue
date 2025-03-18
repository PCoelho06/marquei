<template>
  <div class="inline-flex items-center justify-center" :class="[
    sizeClasses,
    variantClasses,
    roundedClasses,
    {
      'animate-pulse': pulse,
    }
  ]">
    <CoelhoIcon v-if="icon" :icon="icon" :size="iconSize" class="mr-1" />

    <span v-if="hasContent" :class="{ 'ml-1': icon }">
      <slot>{{ content }}</slot>
    </span>

    <!-- Notification dot -->
    <div v-if="type === 'dot'" class="h-2 w-2 rounded-full" :class="[dotVariantClasses]" />

    <!-- Counter -->
    <span v-if="type === 'counter' && count !== undefined">
      {{ count > max ? `${max}+` : count }}
    </span>
  </div>
</template>

<script setup lang="ts">
import { computed, useSlots } from 'vue';
import type { Component } from 'vue';
import CoelhoIcon from './CoelhoIcon.vue';

type BadgeVariant = 'primary' | 'secondary' | 'success' | 'danger' | 'warning' | 'info';
type BadgeType = 'default' | 'counter' | 'status' | 'dot';
type BadgeSize = 'sm' | 'md' | 'lg';

interface Props {
  variant?: BadgeVariant;
  type?: BadgeType;
  size?: BadgeSize;
  icon?: Component;
  content?: string;
  count?: number;
  max?: number;
  pulse?: boolean;
  rounded?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  variant: 'primary',
  type: 'default',
  size: 'md',
  max: 99,
  pulse: false,
  rounded: false,
});

const hasContent = computed(() => {
  return props.content || props.type === 'counter' || !!slots.default;
});

const variantClasses = computed(() => {
  const variants = {
    primary: 'bg-primary text-white',
    secondary: 'bg-stroke text-dark',
    success: 'bg-green-500 text-white',
    danger: 'bg-red-500 text-white',
    warning: 'bg-yellow-500 text-white',
    info: 'bg-blue-500 text-white',
  };
  return props.type !== 'dot' ? variants[props.variant] : '';
});

const dotVariantClasses = computed(() => {
  const variants = {
    primary: 'bg-primary',
    secondary: 'bg-stroke',
    success: 'bg-green-500',
    danger: 'bg-red-500',
    warning: 'bg-yellow-500',
    info: 'bg-blue-500',
  };
  return variants[props.variant];
});

const sizeClasses = computed(() => {
  const sizes = {
    sm: 'text-xs px-1.5 py-0.5 min-w-[1.25rem]',
    md: 'text-sm px-2 py-1 min-w-[1.5rem]',
    lg: 'text-base px-2.5 py-1.5 min-w-[1.75rem]',
  };
  return sizes[props.size];
});

const iconSize = computed(() => {
  const sizes: Record<BadgeSize, 'xs' | 'sm' | 'md'> = {
    sm: 'xs',
    md: 'sm',
    lg: 'md',
  };
  return sizes[props.size];
});

const roundedClasses = computed(() => {
  if (props.type === 'dot') return '';
  return props.rounded ? 'rounded-full' : 'rounded';
});

const slots = useSlots();
</script>