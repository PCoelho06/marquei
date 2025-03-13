<template>
    <div class="min-h-screen bg-gradient-to-b from-whitten to-white">
        <DefaultHeader :class="{ 'backdrop-blur-sm shadow-md': !isTop }" :isBackgroundDark />
        <slot></slot>
    </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';
import { useWindowScroll } from '@vueuse/core';
import DefaultHeader from '@/components/Header/DefaultHeader.vue';

defineProps<{
    isBackgroundDark: boolean
}>();

const { y } = useWindowScroll();
const isTop = ref(y.value <= 0);

watch(y, (value) => {
    if (value > 0) {
        isTop.value = false;
    } else {
        isTop.value = true;
    }
});
</script>
