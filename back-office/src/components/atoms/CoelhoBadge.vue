<template>
  <div class="inline-flex items-center justify-center" :class="{
    'rounded-full': rounded && type !== 'dot',
    'rounded': !rounded && type !== 'dot',
    'text-xs px-1.5 py-0.5 min-w-[1.25rem]': size === 'sm',
    'text-sm px-2 py-1 min-w-[1.5rem]': size === 'md',
    'text-base px-2.5 py-1.5 min-w-[1.75rem]': size === 'lg',
    'bg-primary text-white': variant === 'primary' && type !== 'dot',
    'bg-stroke text-dark': variant === 'secondary' && type !== 'dot',
    'bg-green-500 text-white': variant === 'success' && type !== 'dot',
    'bg-red-500 text-white': variant === 'danger' && type !== 'dot',
    'bg-yellow-500 text-white': variant === 'warning' && type !== 'dot',
    'bg-blue-500 text-white': variant === 'info' && type !== 'dot',
    'animate-pulse': pulse,
  }">
    <CoelhoIcon v-if="icon" :icon="icon" :size="size === 'sm' ? 'xs' : size === 'md' ? 'sm' : 'md'" class="mr-1" />

    <span v-if="hasContent" :class="{ 'ml-1': icon, 'flex items-center': true }">
      <slot>{{ content }}</slot>
      <button v-if="closeable" @click.stop="$emit('close')" :class="{
        'ml-2': true,
        'text-stroke': variant !== 'secondary' && type !== 'dot',
        'text-strokedark ': variant === 'secondary' && type !== 'dot',
      }">
        <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
        </svg>
      </button>
    </span>

    <div v-if="type === 'dot'" class="h-2 w-2 rounded-full" :class="{
      'bg-primary': variant === 'primary',
      'bg-stroke': variant === 'secondary',
      'bg-green-500': variant === 'success',
      'bg-red-500': variant === 'danger',
      'bg-yellow-500': variant === 'warning',
      'bg-blue-500': variant === 'info',
    }" />

    <span v-if="type === 'counter' && count !== undefined">
      {{ count > max ? `${max}+` : count }}
    </span>
  </div>
</template>

<script setup lang="ts">
import { computed, useSlots } from 'vue';
import type { Component } from 'vue';

import { CoelhoIcon } from '..';

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
  closeable?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  variant: 'primary',
  type: 'default',
  size: 'md',
  max: 99,
  pulse: false,
  rounded: false,
  closeable: false,
});

const hasContent = computed(() => {
  return props.content || props.type === 'counter' || !!slots.default;
});

const slots = useSlots();
</script>