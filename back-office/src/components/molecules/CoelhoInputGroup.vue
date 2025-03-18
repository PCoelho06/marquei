<template>
  <div class="space-y-1">
    <!-- Label -->
    <label v-if="label" :for="inputId" class="block mb-2">
      <CoelhoText weight="medium" size="sm">
        {{ label }}
      </CoelhoText>
      <span v-if="required" class="text-red-500">*</span>
    </label>

    <!-- Input Group Container -->
    <div class="relative flex">
      <!-- Prefix -->
      <div v-if="prefix || $slots.prefix"
        class="flex items-center px-3 border border-r-0 border-stroke rounded-l-md bg-whitten"
        :class="{ 'opacity-50': disabled }">
        <CoelhoIcon v-if="prefix" :icon="prefix" size="sm" class="text-dark" />
        <slot name="prefix" />
      </div>

      <!-- Dynamic Form Component -->
      <component :is="componentType" :id="inputId" :prefix="prefix || $slots.prefix ? true : false"
        :suffix="suffix || $slots.suffix || action ? true : false" :type="type" v-model="inputValue" v-bind="$attrs"
        :disabled="disabled" :placeholder="placeholder" :options="options" :multiple="multiple" :searchable="searchable"
        class="flex-1" :error="!!error" @blur="handleBlur" />

      <!-- Suffix -->
      <div v-if="suffix || $slots.suffix" class="flex items-center px-3 border border-l-0 border-stroke bg-whitten"
        :class="[
          { 'opacity-50': disabled },
          { 'rounded-r-md': !action }
        ]">
        <CoelhoIcon v-if="suffix" :icon="suffix" size="sm" class="text-dark" />
        <slot name="suffix" />
      </div>

      <!-- Action Button -->
      <CoelhoButton v-if="action" :variant="actionVariant" :disabled="disabled" class="rounded-l-none px-4"
        @click="$emit('action')">
        <CoelhoIcon v-if="actionIcon" :icon="actionIcon" size="sm" class="mr-1" />
        {{ actionLabel }}
      </CoelhoButton>
    </div>

    <!-- Error Message -->
    <CoelhoText v-if="error" size="sm" color="text-red-500" class="mt-1">
      {{ error }}
    </CoelhoText>

    <!-- Helper Text -->
    <CoelhoText v-else-if="helper" size="sm" color="text-gray-500" class="mt-1">
      {{ helper }}
    </CoelhoText>
  </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';

import type { Component } from 'vue';

import { CoelhoInput, CoelhoSelect, CoelhoButton, CoelhoIcon, CoelhoText } from '../';

import type { SelectOption } from '@/types';

interface Props {
  modelValue: string | string[] | number | number[];
  required?: boolean;
  label?: string;
  prefix?: Component;
  suffix?: Component;
  placeholder?: string;
  type?: 'text' | 'number' | 'email' | 'password' | 'search';
  error?: string;
  helper?: string;
  disabled?: boolean;
  action?: boolean;
  actionLabel?: string;
  actionIcon?: Component;
  actionVariant?: 'primary' | 'secondary' | 'danger';
  component?: 'input' | 'select';
  options?: SelectOption[];
  multiple?: boolean;
  searchable?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
  disabled: false,
  actionVariant: 'primary',
  component: 'input',
  multiple: false,
  searchable: false,
  required: false,
  options: () => [],
});

const emit = defineEmits<{
  (e: 'update:modelValue', value: string | string[] | number | number[]): void;
  (e: 'blur', event: FocusEvent): void;
  (e: 'action'): void;
}>();

const inputId = computed(() => `input-${Math.random().toString(36).substr(2, 9)}`);

const componentType = computed(() => {
  const components = {
    'input': CoelhoInput,
    'select': CoelhoSelect,
  };
  return components[props.component];
});

const inputValue = computed({
  get: () => props.modelValue,
  set: (value: string | string[] | number | number[]) => emit('update:modelValue', value),
});

const handleBlur = (event: FocusEvent) => {
  emit('blur', event);
};
</script>
