<template>
    <div class="mb-6 flex items-center" :class="customClasses">
        <input type="checkbox" :id :name="id" />
        <div class="ms-2">
            <span v-if="required" class="text-meta-1">*</span>
            <label for="use-owner-email">{{ label }}</label>
        </div>
    </div>
</template>

<script setup lang="ts">
defineProps<{
    id: string,
    label: string,
    customClasses?: string,
    required?: boolean,
    error?: string
}>()
</script>