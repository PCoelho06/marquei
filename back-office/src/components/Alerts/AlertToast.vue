<template>
    <div :class="color" class="flex items-center w-full border-l-6 px-7 py-4 mb-6 shadow-md md:p-6">
        <ErrorIcon class="fill-current" />
        <div class="w-full">
            <h5 class="text-xl font-bold">{{ title }}</h5>
            <p class="text-sm">{{ message }}</p>
        </div>
    </div>
</template>

<script setup lang="ts">
import { computed } from 'vue'
import ErrorIcon from '../Icons/ErrorIcon.vue';

const props = defineProps<{
    title?: string,
    message: string,
    type: 'error' | 'success' | 'warning' | 'info'
}>()

const color = computed(() => {
    const colors = {
        error: 'border-red-500 text-red-500 bg-red-50',
        success: 'border-green-500 text-green-500 bg-green-50',
        warning: 'border-yellow-500 text-yellow-500 bg-yellow-50',
        info: 'border-blue-500 text-blue-500 bg-blue-50',
    }
    return colors[props.type] || colors.error
})
</script>