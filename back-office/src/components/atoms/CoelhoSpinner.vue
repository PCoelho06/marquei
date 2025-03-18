<template>
    <div class="flex" :class="{
        'min-w-4': size === 'sm',
        'min-w-6': size === 'md',
        'min-w-8': size === 'lg',
        'min-w-12': size === 'xl'
    }" role="status" :aria-label="ariaLabel">
        <!-- Spinner circulaire -->
        <template v-if="type === 'spinner'">
            <svg class="animate-spin" :class="{
                'h-4 w-4': size === 'sm',
                'h-6 w-6': size === 'md',
                'h-8 w-8': size === 'lg',
                'h-12 w-12': size === 'xl'
            }" viewBox="0 0 24 24" fill="none">
                <circle class="opacity-25" :class="{
                    'stroke-primary': color === 'primary',
                    'stroke-stroke': color === 'secondary',
                    'stroke-white': color === 'white',
                    'stroke-green-500': color === 'success',
                    'stroke-red-500': color === 'danger',
                    'stroke-yellow-500': color === 'warning'
                }" cx="12" cy="12" r="10" stroke-width="4" />
                <path class="opacity-75" :class="[{
                    'fill-primary': color === 'primary',
                    'fill-stroke': color === 'secondary',
                    'fill-white': color === 'white',
                    'fill-green-500': color === 'success',
                    'fill-red-500': color === 'danger',
                    'fill-yellow-500': color === 'warning'
                }]"
                    d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z" />
            </svg>
        </template>

        <!-- Points -->
        <template v-else-if="type === 'dots'">
            <div class="flex space-x-1 items-center">
                <div v-for="n in 3" class="animate-bounce rounded-full" :class="{
                    'h-1 w-1': size === 'sm',
                    'h-2 w-2': size === 'md',
                    'h-3 w-3': size === 'lg',
                    'h-4 w-4': size === 'xl',
                    'bg-primary': color === 'primary',
                    'bg-stroke': color === 'secondary',
                    'bg-white': color === 'white',
                    'bg-green-500': color === 'success',
                    'bg-red-500': color === 'danger',
                    'bg-yellow-500': color === 'warning'
                }" :style="{ animationDelay: (n - 1) * 150 + 'ms' }" />
            </div>
        </template>

        <!-- Barre de progression linéaire -->
        <template v-else-if="type === 'linear'">
            <div class="relative overflow-hidden w-full" :class="{
                'h-1': size === 'sm',
                'h-2': size === 'md',
                'h-3': size === 'lg',
                'h-4': size === 'xl',
            }">
                <div class="absolute inset-0 animate-linear-progress" :class="{
                    'bg-primary': color === 'primary',
                    'bg-stroke': color === 'secondary',
                    'bg-white': color === 'white',
                    'bg-green-500': color === 'success',
                    'bg-red-500': color === 'danger',
                    'bg-yellow-500': color === 'warning'
                }" />
            </div>
        </template>
    </div>
</template>

<script setup lang="ts">
type SpinnerColor = 'primary' | 'secondary' | 'white' | 'success' | 'danger' | 'warning';

interface Props {
    type?: 'spinner' | 'dots' | 'linear';
    size?: 'sm' | 'md' | 'lg' | 'xl';
    color?: SpinnerColor;
    ariaLabel?: string;
}

withDefaults(defineProps<Props>(), {
    type: 'spinner',
    size: 'md',
    color: 'primary',
    ariaLabel: 'Loading',
});

</script>

<style scoped>
@keyframes linear-progress {
    0% {
        left: -100%;
        width: 100%;
    }

    50% {
        left: 100%;
        width: 100%;
    }

    100% {
        left: 100%;
        width: 0%;
    }
}

.animate-linear-progress {
    animation: linear-progress 2s infinite;
}
</style>