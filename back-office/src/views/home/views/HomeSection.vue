<template>
    <div class="container mx-auto grid grid-cols-2 my-8 p-8" ref="target">
        <div class="my-auto"
            :class="{ 'invisible': !targetIsVisible, 'animate-fadein': targetIsVisible, 'order-1': isEven, 'order-2': !isEven }">
            <slot name="content" />
        </div>
        <div class="size-full"
            :class="{ 'invisible': !targetIsVisible, 'animate-slidein-right': targetIsVisible && isEven, 'animate-slidein-left': targetIsVisible && !isEven, 'order-2': isEven, 'order-1': !isEven }">
            <slot name="illustration" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { useTemplateRef } from 'vue';
import { useElementVisibility } from '@vueuse/core';

withDefaults(defineProps<{
    isEven?: boolean;
}>(), {
    isEven: true,
});

const target = useTemplateRef('target');

const targetIsVisible = useElementVisibility(target);
</script>