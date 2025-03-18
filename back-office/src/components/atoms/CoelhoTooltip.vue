<template>
    <div class="relative inline-block" @mouseenter="show" @mouseleave="hide">
        <!-- Contenu déclencheur -->
        <div ref="trigger" class="inline-block">
            <slot />
        </div>

        <!-- Tooltip -->
        <Transition enter-active-class="transition duration-200 ease-out" enter-from-class="opacity-0 scale-95"
            enter-to-class="opacity-100 scale-100" leave-active-class="transition duration-150 ease-in"
            leave-from-class="opacity-100 scale-100" leave-to-class="opacity-0 scale-95">
            <div v-if="isVisible" ref="tooltip" :class="[
                'absolute z-50 px-2 py-1 text-white rounded shadow-lg whitespace-nowrap',
                positionClasses,
                sizeClasses,
                variantClasses,
            ]" role="tooltip">
                {{ content }}
                <!-- Flèche -->
                <div :class="[
                    'absolute w-2 h-2 rotate-45',
                    arrowPositionClasses,
                    variantClasses,
                ]" />
            </div>
        </Transition>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onBeforeUnmount } from 'vue';

interface Props {
    content: string;
    position?: 'top' | 'right' | 'bottom' | 'left';
    variant?: 'primary' | 'dark' | 'danger' | 'success';
    size?: 'sm' | 'md' | 'lg';
    delay?: number;
}

const props = withDefaults(defineProps<Props>(), {
    position: 'top',
    variant: 'dark',
    size: 'md',
    delay: 0,
});

const isVisible = ref(false);
const trigger = ref<HTMLElement | null>(null);
const tooltip = ref<HTMLElement | null>(null);
let showTimeout: number | null = null;

// Classes de position
const positionClasses = computed(() => {
    const positions = {
        top: '-top-2 left-1/2 -translate-x-1/2 -translate-y-full',
        right: 'top-1/2 -right-2 translate-x-full -translate-y-1/2',
        bottom: '-bottom-2 left-1/2 -translate-x-1/2 translate-y-full',
        left: 'top-1/2 -left-2 -translate-x-full -translate-y-1/2',
    };
    return positions[props.position];
});

// Classes de la flèche
const arrowPositionClasses = computed(() => {
    const positions = {
        top: 'bottom-0 left-1/2 -translate-x-1/2 translate-y-1/2',
        right: 'left-0 top-1/2 -translate-x-1/2 -translate-y-1/2',
        bottom: 'top-0 left-1/2 -translate-x-1/2 -translate-y-1/2',
        left: 'right-0 top-1/2 translate-x-1/2 -translate-y-1/2',
    };
    return positions[props.position];
});

// Classes de taille
const sizeClasses = computed(() => {
    const sizes = {
        sm: 'text-xs py-0.5 px-1.5',
        md: 'text-sm py-1 px-2',
        lg: 'text-base py-1.5 px-3',
    };
    return sizes[props.size];
});

// Classes de variante
const variantClasses = computed(() => {
    const variants = {
        primary: 'bg-primary',
        dark: 'bg-dark',
        danger: 'bg-red-500',
        success: 'bg-green-500',
    };
    return variants[props.variant];
});

// Méthodes
const show = () => {
    if (props.delay) {
        showTimeout = window.setTimeout(() => {
            isVisible.value = true;
        }, props.delay);
    } else {
        isVisible.value = true;
    }
};

const hide = () => {
    if (showTimeout) {
        clearTimeout(showTimeout);
    }
    isVisible.value = false;
};

// Nettoyage
onBeforeUnmount(() => {
    if (showTimeout) {
        clearTimeout(showTimeout);
    }
});
</script>