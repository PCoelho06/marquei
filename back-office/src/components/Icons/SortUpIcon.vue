<template>
    <SortDownIcon :size class="rotate-180" />
</template>

<script setup lang="ts">
import SortDownIcon from './SortDownIcon.vue'

defineProps<{
    size: 'sm' | 'md' | 'lg'
}>()
</script>