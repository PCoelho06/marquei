<template>
    <div class="relative">
        <label v-if="label" :for="id" class="block mb-2 text-sm font-medium text-dark">
            {{ label }}
        </label>

        <div class="relative">
            <div v-if="leftIcon" class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                <component :is="leftIcon" class="h-5 w-5 text-gray-400" />
            </div>

            <input :id="id" :type="type" :value="modelValue" :disabled="disabled" :placeholder="placeholder" :class="[
                'w-full rounded-md font-medium transition-colors',
                'border border-stroke focus:border-primary focus:ring-1 focus:ring-primary',
                {
                    'pl-10': leftIcon,
                    'pr-10': rightIcon,
                    'bg-whitten cursor-not-allowed opacity-50': disabled,
                    'text-xs px-2 py-1': size === 'sm',
                    'text-sm px-3 py-2': size === 'md',
                    'text-base px-4 py-3': size === 'lg',
                    'rounded-l-none': prefix,
                    'rounded-r-none': suffix,
                }
            ]" @input="$emit('update:modelValue', (<HTMLInputElement>$event.target).value)"
                @blur="$emit('blur', $event)" />

            <div v-if="rightIcon" class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                <component :is="rightIcon" class="h-5 w-5 text-gray-400" />
            </div>
        </div>

        <p v-if="helperText" class="mt-1 text-xs text-gray-500">{{ helperText }}</p>
    </div>
</template>

<script setup lang="ts">
import type { Component } from 'vue';

interface Props {
    modelValue?: string | number | string[] | number[];
    type?: 'text' | 'number' | 'email' | 'password' | 'search';
    label?: string;
    placeholder?: string;
    size?: 'sm' | 'md' | 'lg';
    disabled?: boolean;
    helperText?: string;
    leftIcon?: Component;
    rightIcon?: Component;
    prefix?: boolean;
    suffix?: boolean;
    id?: string;
}

withDefaults(defineProps<Props>(), {
    type: 'text',
    size: 'md',
    disabled: false,
    id: `input-${Math.random().toString(36).substr(2, 9)}`,
});

defineEmits<{
    (e: 'update:modelValue', value: string): void;
    (e: 'blur', event: FocusEvent): void;
}>();
</script>