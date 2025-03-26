<template>
    <span :class="{
        'text-xs': size === 'xs',
        'text-sm': size === 'sm',
        'text-base': size === 'base',
        'text-lg': size === 'lg',
    }">
        <span class="font-bold">{{ label }}</span>
        <span class="mx-1">:</span>
        <span class="text-gray-500">{{ content }}</span>
    </span>
</template>

<script setup lang="ts">
interface Props {
    size?: 'xs' | 'sm' | 'base' | 'lg';
    label?: string;
    content?: string;
}

withDefaults(defineProps<Props>(), {
    size: 'base',
});
</script>