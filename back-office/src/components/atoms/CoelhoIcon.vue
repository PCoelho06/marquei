<template>
    <div class="inline-flex" :class="[sizeClasses, colorClass]" :style="rotationStyle">
        <component :is="icon" v-bind="$attrs" :class="[
            'transition-transform',
            { 'animate-spin': spin }
        ]" />
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import type { Component } from 'vue';

interface Props {
    icon: Component;
    size?: 'xs' | 'sm' | 'md' | 'lg' | 'xl' | number;
    color?: string;
    rotation?: number;
    spin?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    size: 'md',
    color: 'currentColor',
    rotation: 0,
    spin: false,
});

// Classes de taille prédéfinies
const sizeClasses = computed(() => {
    if (typeof props.size === 'number') {
        return `w-[${props.size}px] h-[${props.size}px]`;
    }

    const sizes = {
        xs: 'w-3 h-3',
        sm: 'w-4 h-4',
        md: 'w-5 h-5',
        lg: 'w-6 h-6',
        xl: 'w-8 h-8',
    };

    return sizes[props.size];
});

// Classe de couleur
const colorClass = computed(() => {
    // Si c'est une couleur Tailwind
    if (props.color.startsWith('text-')) {
        return props.color;
    }
    // Sinon, on applique la couleur directement
    return `text-[${props.color}]`;
});

// Style de rotation
const rotationStyle = computed(() => ({
    transform: props.rotation ? `rotate(${props.rotation}deg)` : undefined,
}));
</script>