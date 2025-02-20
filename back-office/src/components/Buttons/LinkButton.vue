<template>
    <router-link :to class="w-full cursor-pointer rounded-lg border px-4 font-medium transition text-center"
        :class="color, size" :target="props.newTab ? '_blank' : ''" rel="nofollow noopener">
        {{ value }}
    </router-link>
</template>

<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{ to: string, newTab: boolean, value: string, type?: 'primary' | 'secondary' | 'success' | 'danger' | 'warning', size?: 'sm' | 'md' | 'xl' }>()

const buttonType = props.type || 'primary'
const buttonSize = props.size || 'md'

const color = computed(() => {
    const colors = {
        danger: 'border-red-500 bg-red-500 hover:bg-red-500/90 text-white',
        success: 'border-green-500 bg-green-500 hover:bg-green-500/90 text-white',
        warning: 'border-yellow-500 bg-yellow-500 hover:bg-yellow-500/90 text-white',
        primary: 'border-primary bg-primary hover:bg-primary/90 text-white',
        secondary: 'border-white bg-white hover:bg-white/90 text-primary',
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