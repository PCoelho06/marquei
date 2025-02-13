<template>
    <div class="mb-5 mt-6">
        <input type="submit" :value
            class="w-full cursor-pointer rounded-lg border border-primary bg-primary p-4 font-medium text-white transition hover:bg-opacity-90" />
    </div>
</template>

<script setup lang="ts">
defineProps<{ value: string }>()
</script>