<template>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="inline"
        :class="classSize[size]">
        <path
            d="M11.744,18c-.757,0-1.48-.323-1.985-.886L3.328,10.587c-.602-.657-.747-1.535-.402-2.317,.345-.783,1.09-1.27,1.945-1.27h13.745c.854,0,1.6,.486,1.944,1.269s.2,1.66-.376,2.291l-6.487,6.589c-.474,.528-1.197,.852-1.953,.852Z" />
    </svg>
</template>

<script setup lang="ts">
defineProps<{
    size: 'sm' | 'md' | 'lg'
}>()

const classSize = {
    sm: 'size-4',
    md: 'size-6',
    lg: 'size-8',
}
</script>