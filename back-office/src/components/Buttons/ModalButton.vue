<template>
    <DefaultButton :value :type :size @click="handleClick" />
    <DefaultModal :title :actionClose="() => showModal = false">
        <template #content>
            <slot id="content"></slot>>
        </template>
        <template #actions>
            <slot id="actions"></slot>
        </template>
    </DefaultModal>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import DefaultModal from '../Modals/DefaultModal.vue';
import DefaultButton from './DefaultButton.vue';

defineProps<{
    value: string,
    type?: 'primary' | 'secondary' | 'success' | 'danger' | 'warning',
    size?: 'sm' | 'md' | 'xl'
    title?: string
}>()

const emit = defineEmits(['click'])

const showModal = ref(false)

const handleClick = () => {
    showModal.value = true
}
</script>