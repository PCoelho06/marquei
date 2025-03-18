<template>
    <label class="inline-flex items-center" :class="{ 'cursor-not-allowed': disabled }">
        <div class="relative">
            <!-- Switch variant -->
            <template v-if="variant === 'switch'">
                <input type="checkbox" class="peer hidden" :checked="modelValue" :disabled="disabled"
                    :indeterminate="indeterminate" @change="handleChange">
                <div class="relative h-5 w-8 rounded-full transition-colors duration-200 ease-in-out" :class="[
                    modelValue ? 'bg-primary' : 'bg-stroke',
                    disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'
                ]">
                    <div class="absolute left-0.5 top-0.5 h-4 w-4 transform rounded-full bg-white shadow-md transition-transform duration-200 ease-in-out"
                        :class="[
                            modelValue ? 'translate-x-3' : 'translate-x-0',
                            disabled ? 'cursor-not-allowed' : 'cursor-pointer'
                        ]" />
                </div>
            </template>

            <!-- Standard checkbox -->
            <template v-else>
                <input type="checkbox" class="peer hidden" :checked="modelValue" :disabled="disabled"
                    :indeterminate="indeterminate" @change="handleChange">
                <div class="flex h-5 w-5 items-center justify-center rounded border-2 transition-colors" :class="[
                    disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer',
                    modelValue ? 'border-primary bg-primary' : 'border-stroke bg-white',
                    indeterminate ? 'border-primary bg-primary' : ''
                ]">
                    <template v-if="modelValue">
                        <svg class="h-3 w-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M5 13l4 4L19 7" />
                        </svg>
                    </template>
                    <template v-else-if="indeterminate">
                        <svg class="h-3 w-3 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="3" d="M4 12h16" />
                        </svg>
                    </template>
                </div>
            </template>
        </div>
        <span v-if="label" class="ml-2 text-sm text-dark" :class="{ 'opacity-50': disabled }">
            {{ label }}
        </span>
    </label>
</template>

<script setup lang="ts">
interface Props {
    modelValue?: boolean;
    label?: string;
    disabled?: boolean;
    indeterminate?: boolean;
    variant?: 'standard' | 'switch';
}

const props = withDefaults(defineProps<Props>(), {
    modelValue: false,
    disabled: false,
    indeterminate: false,
    variant: 'standard'
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: boolean): void;
}>();

const handleChange = (event: Event) => {
    if (!props.disabled) {
        emit('update:modelValue', (event.target as HTMLInputElement).checked);
    }
};
</script>