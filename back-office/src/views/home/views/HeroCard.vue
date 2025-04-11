<template>
    <div class="bg-cover rounded-xl hidden opacity-0" :class="{
        'size-50 animate-[fadeIn_1s_ease-in-out_1200ms_forwards] md:block': size === 'xl',
        'size-40 animate-[fadeIn_1s_ease-in-out_900ms_forwards] lg:block': size === 'lg',
        'size-30 animate-[fadeIn_1s_ease-in-out_600ms_forwards] xl:block': size === 'md',
        'size-20 animate-[fadeIn_1s_ease-in-out_300ms_forwards] xl:block': size === 'sm'
    }" :style="{ 'background-image': `url(${imageUrl})` }">
        <slot />
    </div>
</template>

<script setup lang="ts">
const props = defineProps<{
    size?: 'sm' | 'md' | 'lg' | 'xl';
    image?: string;
}>();

const imageUrl = `'${props.image}'`;
</script>
