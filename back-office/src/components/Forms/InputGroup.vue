<template>
    <div class="mb-6" :class="customClasses">
        <label class="mb-2.5 block font-medium text-black" :for="id">
            {{ props.label }}
            <span v-if="required" class="text-meta-1">*</span>
        </label>
        <div class="relative w-full" v-if="type == 'password'">
            <input :type="passwordVisible ? 'text' : 'password'" :placeholder :value="modelValue" :name="id" :id
                :autocomplete @input="$emit('update:modelValue', (<HTMLTextAreaElement>$event.target).value)"
                class="w-full rounded border-[1.5px] py-3 px-12 font-normal outline-none transition focus:border-primary active:border-primary"
                :class="{ 'border-red-500 text-red-500 bg-red-50': hasError, 'border-stroke text-black bg-transparent': !hasError }" />
            <span class="absolute left-4 top-4" :class="{ 'text-red-500': hasError }">
                <LockIcon />
            </span>
            <button type="button" tabindex="-1" class="absolute right-3 top-1/2 transform -translate-y-1/2"
                @click="togglePassword">
                <EyeSlashIcon v-if="passwordVisible" class="fill-current" />
                <EyeIcon v-else />
            </button>
        </div>
        <div class="relative" v-else>
            <input :type :placeholder :value="modelValue" :name="id" :id :autocomplete :disabled
                @input="$emit('update:modelValue', (<HTMLTextAreaElement>$event.target).value)"
                class="w-full rounded border-[1.5px] py-3 ps-12 pe-3 font-normal outline-none transition focus:border-primary active:border-primary"
                :class="{ 'border-red-500 text-red-500 bg-red-50': hasError, 'bg-gray-100 border-stroke text-gray-700': isDisabled, 'border-stroke text-black bg-transparent': !hasError && !isDisabled }" />
            <span class="absolute left-4 top-4" :class="{ 'text-red-500': hasError }">
                <slot></slot>
            </span>
        </div>
        <p class="text-red-500 text-sm">{{ error }}</p>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'
import LockIcon from '../Icons/LockIcon.vue';
import EyeIcon from '../Icons/EyeIcon.vue';
import EyeSlashIcon from '../Icons/EyeSlashIcon.vue';

const props = defineProps<{
    id: string,
    autocomplete: string,
    label: string,
    type: string,
    placeholder: string,
    modelValue?: string,
    customClasses?: string,
    required?: boolean,
    error?: string,
    disabled?: boolean
}>()

const passwordVisible = ref(false);

const hasError = computed(() => {
    return props.error ? true : false
})

const isDisabled = computed(() => {
    return props.disabled ? true : false
})

const customClasses = computed(() => {
    return props.customClasses ? props.customClasses : ''
})

const required = computed(() => {
    return props.required ? props.required : false
})

const togglePassword = () => {
    passwordVisible.value = !passwordVisible.value;
};
</script>