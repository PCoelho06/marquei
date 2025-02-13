<template>
    <button
        class="my-6 w-full cursor-pointer rounded-lg border border-primary bg-primary p-4 font-medium text-white transition hover:bg-opacity-90">
        {{ value }}
    </button>
</template>

<script setup lang="ts">
defineProps<{ value: string }>()
</script>