<template>
    <div class="flex items-center justify-center" :class="containerClass">
        <div class="relative">
            <!-- Cercle principal -->
            <div class="size-12 rounded-full border-4 border-t-transparent border-primary animate-spin" :class="[
                sizeClasses[size],
                colorClasses[color],
                { 'opacity-25': overlay }
            ]"></div>

            <!-- Texte sous le loader -->
            <span v-if="text" class="mt-2 block text-center text-gray-600" :class="textSizeClasses[size]">
                {{ text }}
            </span>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue';

interface Props {
    size?: 'sm' | 'md' | 'lg';
    color?: 'primary' | 'secondary' | 'white';
    text?: string;
    overlay?: boolean;
    fullScreen?: boolean;
}

// Définition des props avec leurs valeurs par défaut
const props = withDefaults(defineProps<Props>(), {
    size: 'md',
    color: 'primary',
    text: '',
    overlay: false,
    fullScreen: false
});

// Classes pour les différentes tailles
const sizeClasses = {
    sm: 'w-8 h-8 border-2',
    md: 'w-12 h-12 border-4',
    lg: 'w-16 h-16 border-4'
} as const;

// Classes pour les différentes couleurs
const colorClasses = {
    primary: 'border-blue-500',
    secondary: 'border-gray-500',
    white: 'border-white'
} as const;

// Classes pour les tailles de texte
const textSizeClasses = {
    sm: 'text-sm',
    md: 'text-base',
    lg: 'text-lg'
} as const;

// Classes du conteneur
const containerClass = computed(() => ({
    'fixed inset-0 bg-black bg-opacity-50 z-50': props.overlay,
    'min-h-screen': props.fullScreen,
}));
</script>