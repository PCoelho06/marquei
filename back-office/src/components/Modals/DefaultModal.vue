<template>
    <div class="fixed inset-0 bg-stroke/75 flex items-center justify-center z-50 overflow-scroll">
        <div class="bg-white rounded-lg p-8 max-w-md w-full" ref="target">
            <div class="flex items-center mb-6"
                :class="{ 'justify-between': props.title, 'justify-end': !props.title }">
                <h3 class="text-lg font-medium text-gray-900">{{ title }}</h3>
                <button @click="actionClose" class="text-gray-400 hover:text-gray-500 cursor-pointer">
                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="my-3">
                <slot name="content"></slot>
            </div>
            <div class="my-3">
                <slot name="actions"></slot>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { onClickOutside } from '@vueuse/core';

const props = defineProps<{
    title?: string
    actionClose: () => void
}>()

const target = ref(null)

onClickOutside(target, () => {
    props.actionClose()
})
</script>