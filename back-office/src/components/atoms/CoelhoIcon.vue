<template>
    <div class="inline-flex" :class="{
        'w-3 h-3': size === 'xs',
        'w-4 h-4': size === 'sm',
        'w-5 h-5': size === 'md',
        'w-6 h-6': size === 'lg',
        'w-8 h-8': size === 'xl',
        'text-primary': color === 'primary',
        'text-gray-500': color === 'secondary',
        'text-red-500': color === 'danger',
        'text-green-500': color === 'success',
        'text-yellow-500': color === 'warning',
        'text-current': color === 'currentColor',
    }" :style="rotationStyle">
        <component :is="icon" v-bind="$attrs" :class="[
            'transition-transform',
            animate ? { 'animate-pulse': animate === 'pulse', 'animate-bounce': animate === 'bounce', 'animate-ping': animate === 'ping' } : '',
        ]" />
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import type { Component } from 'vue';

interface Props {
    icon: Component;
    size?: 'xs' | 'sm' | 'md' | 'lg' | 'xl';
    color?: 'primary' | 'secondary' | 'danger' | 'success' | 'warning' | 'currentColor';
    rotation?: number;
    animate?: 'pulse' | 'bounce' | 'ping';
}

const props = withDefaults(defineProps<Props>(), {
    size: 'md',
    color: 'currentColor',
    rotation: 0,
});

const rotationStyle = computed(() => ({
    transform: props.rotation ? `rotate(${props.rotation}deg)` : undefined,
}));
</script>