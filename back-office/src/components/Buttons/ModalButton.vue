<template>
    <DefaultButton :value :type :size @click="handleClick" />
    <DefaultModal :showModal :modalTexts :action @cancel="showModal = false" />
</template>

<script setup lang="ts">
import { ref } from 'vue'
import DefaultModal from '../Modals/DefaultModal.vue';
import DefaultButton from './DefaultButton.vue';

defineProps<{
    value: string,
    type?: 'primary' | 'secondary' | 'success' | 'danger' | 'warning',
    size?: 'sm' | 'md' | 'xl'
    modalTexts?: {
        title: string
        content: string
        validate: string
        dismiss: string
    }
    action: () => void
}>()

const emit = defineEmits(['click'])

const showModal = ref(false)

const handleClick = () => {
    showModal.value = true
}
</script>