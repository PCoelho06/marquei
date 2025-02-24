<template>
    <svg xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" fill="currentColor" class="inline"
        :class="classSize[size]">
        <path
            d="M18.873,11.021H5.127a2.126,2.126,0,0,1-1.568-3.56L10.046.872a2.669,2.669,0,0,1,3.939.034l6.431,6.528a2.126,2.126,0,0,1-1.543,3.587ZM12,24.011a2.667,2.667,0,0,1-1.985-.887L3.584,16.6a2.125,2.125,0,0,1,1.543-3.586H18.873a2.125,2.125,0,0,1,1.568,3.558l-6.487,6.589A2.641,2.641,0,0,1,12,24.011Z" />
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