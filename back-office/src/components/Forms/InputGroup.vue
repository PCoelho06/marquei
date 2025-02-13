<script setup lang="ts">
import { computed } from 'vue'

const props = defineProps<{
    id: string,
    autocomplete: string,
    label: string,
    type: string,
    placeholder: string,
    modelValue?: string,
    customClasses?: string,
    required?: boolean,
    error?: string
}>()

const hasError = computed(() => {
    return props.error ? true : false
})

const customClasses = computed(() => {
    return props.customClasses ? props.customClasses : ''
})

const required = computed(() => {
    return props.required ? props.required : false
})
</script>

<template>
    <div class="mb-6" :class="customClasses">
        <label class="mb-2.5 block font-medium text-black" :for="id">
            {{ props.label }}
            <span v-if="required" class="text-meta-1">*</span>
        </label>
        <div class="relative">
            <input :type :placeholder :value="modelValue" :name="id" :id="id" :autocomplete
                @input="$emit('update:modelValue', (<HTMLTextAreaElement>$event.target).value)"
                class="w-full rounded border-[1.5px] py-3 px-5 font-normal outline-none transition focus:border-primary active:border-primary"
                :class="{ 'border-red-500 text-red-500 bg-red-50': hasError, 'border-stroke text-black bg-transparent': !hasError }" />
            <span class="absolute right-4 top-4" :class="{ 'text-red-500': hasError, 'text-stroke': !hasError }">
                <slot></slot>
            </span>
        </div>
        <p class="text-red-500 text-sm">{{ error }}</p>
    </div>
</template>