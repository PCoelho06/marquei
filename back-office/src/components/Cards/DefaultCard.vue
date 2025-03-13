<template>
    <div class="flex flex-col rounded-sm border border-stroke bg-white shadow-xl">
        <div v-if="cardTitle" class="border-b border-stroke py-4 px-6.5">
            <h3 class="font-medium text-dark">{{ props.cardTitle }}</h3>
        </div>
        <div class="p-4">
            <slot></slot>
        </div>
        <div class="flex space-x-4 m-2 mt-auto">
            <slot name="action"></slot>
        </div>
    </div>
</template>

<script setup lang="ts">
const props = defineProps<{
    cardTitle?: string,
}>()
</script>