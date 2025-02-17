<template>
    <button class="w-full cursor-pointer rounded-lg border p-4 font-medium transition bg-transparent"
        :class="color, size">
        {{ value }}
    </button>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{ value: string, type?: 'primary' | 'secondary' | 'success' | 'danger' | 'warning', size?: 'sm' | 'md' | 'xl' }>()

const buttonType = props.type || 'primary'
const buttonSize = props.size || 'md'

const color = computed(() => {
    const colors = {
        danger: 'border-red-500 hover:border-red-500/90 text-red-500 hover:text-red-500/90',
        success: 'border-green-500 hover:border-green-500 text-green-500 hover:text-green-500/90',
        warning: 'border-yellow-500 hover:border-yellow-500 text-yellow-500 hover:text-yellow-500/90',
        primary: 'border-primary hover:border-primary/90 text-primary hover:text-primary/90',
        secondary: 'border-dark hover:border-dark/90 text-dark hover:text-dark/90',
    }
    return colors[buttonType] || colors.primary
})

const size = computed(() => {
    const sizes = {
        sm: 'py-2 text-sm',
        md: 'py-4 text-base',
        xl: 'py-6 text-lg',
    }
    return sizes[buttonSize] || sizes.md
})
</script>