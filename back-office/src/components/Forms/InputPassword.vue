<template>
    <div class="relative w-full">
        <input
            :type="passwordVisible ? 'text' : 'password'"
            :placeholder
            class="w-full border rounded px-4 py-2 pr-10 text-base"
            :value="modelValue"
            :name="id" :id :autocomplete
            @input="$emit('update:modelValue', (<HTMLTextAreaElement>$event.target).value)"
                class="w-full rounded border-[1.5px] py-3 px-5 font-normal outline-none transition focus:border-primary active:border-primary"
                :class="{ 'border-red-500 text-red-500 bg-red-50': hasError, 'border-stroke text-black bg-transparent': !hasError }"
        />
        <button
            type="button"
            class="absolute right-3 top-1/2 transform -translate-y-1/2 text-gray-500 hover:text-gray-700"
            @click="togglePassword"
        >
            <svg v-if="passwordVisible" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13.875 18.825A10.05 10.05 0 0112 19c-5.523 0-10-4.477-10-10a9.956 9.956 0 011.775-5.625M6.375 6.375A9.954 9.954 0 0112 5c5.523 0 10 4.477 10 10a9.956 9.956 0 01-1.775 5.625M15.375 15.375A3 3 0 1115.375 9 3 3 0 0115.375 15.375z"/>
            </svg>
            <svg v-else xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3l18 18M4.318 4.318A12.954 12.954 0 0012 2c5.523 0 10 4.477 10 10a12.954 12.954 0 01-2.318 7.682M6.343 6.343A5 5 0 0012 7a5 5 0 005.657 5.657"/>
            </svg>
        </button>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';

const props = defineProps<{
    id: string,
    autocomplete: string,
    label: string,
    type: string,
    placeholder: string,
    modelValue?: string,
    error?: string
}>()

const passwordVisible = ref(false);

const hasError = computed(() => {
    return props.error ? true : false
})

const togglePassword = () => {
    passwordVisible.value = !passwordVisible.value;
};
</script>
