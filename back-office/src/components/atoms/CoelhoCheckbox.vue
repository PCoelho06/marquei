<template>
    <label class="inline-flex items-center" :class="{ 'cursor-not-allowed': disabled }">
        <div class="relative">
            <template v-if="variant === 'switch'">
                <input type="checkbox" class="peer hidden" :checked="modelValue" :disabled="disabled"
                    :indeterminate="indeterminate" @change="handleChange">
                <div class="flex items-center gap-3">
                    <span v-if="switchOffLabel" :class="[!modelValue ? 'text-gray-900' : 'text-gray-500']">
                        {{ switchOffLabel }}
                    </span>
                    <div class="relative rounded-full transition-colors duration-200 ease-in-out border-2 border-transparent"
                        :class="[
                            { 'w-8 h-4': size === 'sm', 'w-12 h-6': size === 'md', 'w-16 h-8': size === 'lg' },
                            modelValue ? 'bg-primary' : 'bg-dark',
                            disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer'
                        ]">
                        <div class="absolute transform rounded-full bg-white shadow-md transition-transform duration-200 ease-in-out"
                            :class="[
                                { 'w-3 h-3': size === 'sm', 'w-5 h-5': size === 'md', 'w-7 h-7': size === 'lg' },
                                modelValue ? { 'translate-x-4': size === 'sm', 'translate-x-6': size === 'md', 'translate-x-8': size === 'lg' } : 'translate-x-0',
                                disabled ? 'cursor-not-allowed' : 'cursor-pointer'
                            ]" />
                    </div>
                    <span v-if="switchOnLabel" :class="[modelValue ? 'text-gray-900' : 'text-gray-500']">
                        {{ switchOnLabel }}
                    </span>
                </div>
            </template>

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
    switchOnLabel?: string;
    switchOffLabel?: string;
    size?: 'sm' | 'md' | 'lg';
}

const props = withDefaults(defineProps<Props>(), {
    modelValue: false,
    disabled: false,
    indeterminate: false,
    variant: 'standard',
    size: 'md'
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