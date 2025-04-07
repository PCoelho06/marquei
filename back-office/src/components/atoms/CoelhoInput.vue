<template>
    <div class="relative">
        <label v-if="label" :for="id" class="block mb-2 text-sm font-medium text-dark">
            <CoelhoText weight="medium" size="sm">
                {{ label }}
            </CoelhoText>
            <span v-if="required" class="text-red-500">*</span>
        </label>

        <div class="relative">
            <CoelhoIcon v-if="leftIcon" :icon="leftIcon" size="xl" color="secondary"
                class="absolute left-2 top-1/2 transform -translate-y-1/2" />

            <input :id :value="modelValue" :disabled :placeholder :autocomplete
                :type="type === 'password' ? (passwordVisible ? 'text' : 'password') : type" :class="[
                    'w-full rounded-md font-medium transition-colors',
                    'border border-stroke focus:border-primary focus:ring-1 focus:ring-primary',
                    {
                        'pl-13': leftIcon,
                        'pr-10': rightIcon,
                        'bg-whitten cursor-not-allowed opacity-50': disabled,
                        'text-xs px-2 py-1': size === 'sm',
                        'text-sm px-3 py-2': size === 'md',
                        'text-base px-4 py-3': size === 'lg',
                    }
                ]" @input="$emit('update:modelValue', (<HTMLInputElement>$event.target).value)"
                @blur="$emit('blur', $event)" />

            <button v-if="type === 'password'" type="button" tabindex="-1"
                class="absolute flex items-center right-3 top-1/2 transform -translate-y-1/2" @click="togglePassword">
                <CoelhoIcon v-if="passwordVisible" :icon="EyeSlashIcon" size="lg" color="secondary" />
                <CoelhoIcon v-else :icon="EyeIcon" size="lg" color="secondary" />
            </button>
            <CoelhoIcon v-if="rightIcon && type !== 'password'" :icon="rightIcon" size="xl" color="secondary"
                class="absolute right-2 top-1/2 transform -translate-y-1/2" />
            <CoelhoText v-if="suffix" size="sm" color="secondary"
                class="absolute right-2 top-1/2 transform -translate-y-1/2">
                {{ suffix }}
            </CoelhoText>
        </div>

        <CoelhoText v-if="error" size="sm" color="danger" class="mt-1">
            {{ error }}
        </CoelhoText>
        <CoelhoText v-else-if="helper" size="sm" color="secondary" class="mt-1">
            {{ helper }}
        </CoelhoText>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import type { Component } from 'vue';

import { CoelhoIcon, CoelhoText } from '..';
import { EyeIcon, EyeSlashIcon } from '@heroicons/vue/24/solid';

interface Props {
    modelValue: string | number | string[] | number[];
    id?: string;
    size?: 'sm' | 'md' | 'lg';
    type?: 'text' | 'number' | 'email' | 'tel' | 'password' | 'search' | 'date' | 'time' | 'datetime-local';
    label?: string;
    error?: string;
    helper?: string;
    disabled?: boolean;
    required?: boolean;
    placeholder?: string;
    autocomplete?: string;
    leftIcon?: Component;
    rightIcon?: Component;
    suffix?: string;
}

const passwordVisible = ref(false);

withDefaults(defineProps<Props>(), {
    type: 'text',
    size: 'md',
    disabled: false,
    id: `input-${Math.random().toString(36).substring(2, 11)}`,
    required: false,
    autocomplete: 'off',
});

defineEmits<{
    (e: 'update:modelValue', value: string): void;
    (e: 'blur', event: FocusEvent): void;
}>();

const togglePassword = () => {
    passwordVisible.value = !passwordVisible.value;
};
</script>