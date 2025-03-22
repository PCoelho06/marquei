<template>
    <component :is="resolvedComponent" v-bind="linkProps" :type="type" :disabled="disabled || loading" :class="[
        'inline-flex items-center justify-center rounded-md font-medium transition-colors',
        {
            'bg-primary text-white hover:bg-primary/80': variant === 'primary' && !outlined,
            'bg-whitten text-primary hover:bg-stroke': variant === 'secondary' && !outlined,
            'bg-red-600 text-white hover:bg-red-700': variant === 'danger' && !outlined,
            'bg-green-600 text-white hover:bg-green-700': variant === 'success' && !outlined,
            'bg-transparent border': outlined,
            'border-primary text-primary hover:text-primary/80 hover:border-primary/80': variant === 'primary' && outlined,
            'border-whitten text-whitten hover:text-stroke hover:border-stroke': variant === 'secondary' && outlined,
            'border-red-600 text-red-600 hover:text-red-700 hover:border-red-700': variant === 'danger' && outlined,
            'border-green-600 text-green-600 hover:text-green-700 hover:border-green-700': variant === 'success' && outlined,
            'cursor-pointer': !disabled && !loading,
            'opacity-50 cursor-not-allowed': disabled,
            'cursor-wait': loading,
            'text-xs px-2 py-1': size === 'sm',
            'text-sm px-4 py-2': size === 'md',
            'text-base px-6 py-3': size === 'lg',
        }
    ]" @click="!disabled && !loading ? emit('click', $event) : undefined">
        <CoelhoIcon v-if="icon && !loading" :icon="icon" :size
            :class="{ 'mr-1': !!$slots.default, 'mx-auto': !$slots.default }" />
        <span v-if="loading" class="mr-2">
            <svg class="animate-spin h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z">
                </path>
            </svg>
        </span>
        <slot />
    </component>
</template>

<script setup lang="ts">
import { computed, inject } from 'vue';
import type { Router } from 'vue-router';
import { CoelhoIcon } from '..';

import type { Component } from 'vue';

interface Props {
    variant?: 'primary' | 'secondary' | 'danger' | 'success';
    outlined?: boolean,
    size?: 'sm' | 'md' | 'lg';
    disabled?: boolean;
    loading?: boolean;
    type?: 'button' | 'submit' | 'reset';
    icon?: Component;
    href?: string;
    to?: string;
}

const props = withDefaults(defineProps<Props>(), {
    variant: 'primary',
    size: 'md',
    disabled: false,
    outlined: false,
    loading: false,
    type: 'button'
});

const router = inject('router') as Router | undefined;

const emit = defineEmits<{
    (e: 'click', event: MouseEvent): void;
}>();

const isExternal = computed(() => {
    if (!props.href) return false;
    return props.href.startsWith('http') || props.href.startsWith('//');
});

const resolvedComponent = computed(() => {
    if (!props.to && !props.href) return 'button';
    if (!router || !props.to) return 'a';
    return 'router-link';
});

const linkProps = computed(() => {
    if (props.disabled) return {};

    if (props.to) {
        if (!router) {
            return { href: props.to };
        }
        return { to: props.to };
    }

    if (isExternal.value) {
        return {
            href: props.href,
            target: '_blank',
            rel: 'noopener noreferrer'
        };
    }

    return { href: props.href };
});
</script>