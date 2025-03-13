<template>
    <h1 class="flex items-center justify-center gap-2"
        :class="[variant === 'dark' ? 'text-primary' : 'text-white', size === 'sm' ? 'text-3xl font-semibold' : size === 'md' ? 'text-5xl font-bold' : 'text-7xl font-extrabold']">
        <img src="@/assets/images/logos/icon.svg" alt="logo" class="w-16 h-16 inline-block" />
        Marquei
    </h1>
</template>

<script setup lang="ts">
withDefaults(defineProps<{
    variant?: 'light' | 'dark'
    size?: 'sm' | 'md' | 'lg'

}>(), {
    variant: 'dark',
    size: 'md'
})
</script>