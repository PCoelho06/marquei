<template>
    <div class="bg-white rounded-xl perspective-distant" :class="{
        'size-50': size === 'xl',
        'size-40': size === 'lg',
        'size-30': size === 'md',
        'size-20': size === 'sm',
    }" ref="target">
        <div class="absolute top-0 left-0 w-full h-full rounded-xl mix-blend-hard-light perspective-distant" ref="glow">
        </div>
        <slot />
    </div>
</template>

<script setup lang="ts">
import { useTemplateRef, watchEffect } from 'vue'
import { useMouseInElement } from '@vueuse/core'

defineProps<{
    size?: 'sm' | 'md' | 'lg' | 'xl';
}>();

const target = useTemplateRef('target');
const glowElement = useTemplateRef('glow');
const { x, y, elementX, elementY, elementHeight, elementWidth, isOutside } = useMouseInElement(target);

watchEffect(() => {
    if (!target.value || !glowElement.value) return;
    if (!isOutside.value) {
        const angleY = -(60 / elementWidth.value) * (elementX.value - elementWidth.value / 2);
        const angleX = (60 / elementHeight.value) * (elementY.value - elementHeight.value / 2);

        const glowX = elementX.value / elementWidth.value * 100
        const glowY = elementY.value / elementHeight.value * 100

        target.value.style.transform = `rotateY(${angleY}deg) rotateX(${angleX}deg) scale(1.05)`;
        target.value.style.transition = 'transform 0.1s ease-out';

        glowElement.value.style.transform = `rotateY(${angleY}deg) rotateX(${angleX}deg) scale(1.05)`;
        glowElement.value.style.background = `radial-gradient(at ${glowX}% ${glowY}%, rgba(255, 255, 255, 0.5), rgba(255, 255, 255, 0) 70%)`;
        glowElement.value.style.transition = 'transform 0.1s ease-out';
    } else {
        target.value.style.transform = 'rotateY(0deg) rotateX(0deg)';
        glowElement.value.style.transform = 'rotateY(0deg) rotateX(0deg)';
        glowElement.value.style.background = `transparent`;
    }
});
</script>