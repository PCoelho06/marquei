<template>
    <div class="space-y-2" :class="{ 'flex flex-col': direction === 'vertical' }">
        <label v-for="option in options" :key="option.value" class="inline-flex items-center mr-4"
            :class="{ 'cursor-not-allowed': disabled }">
            <div class="relative">
                <input type="radio" class="peer hidden" :name="name" :value="option.value"
                    :checked="modelValue === option.value" :disabled="disabled"
                    @change="handleChange($event, option.value)">
                <div class="flex h-5 w-5 items-center justify-center rounded-full border-2 transition-colors" :class="[
                    disabled ? 'opacity-50 cursor-not-allowed' : 'cursor-pointer',
                    modelValue === option.value ? 'border-primary' : 'border-stroke',
                ]">
                    <div class="h-2.5 w-2.5 rounded-full transition-colors" :class="[
                        modelValue === option.value ? 'bg-primary' : 'bg-transparent'
                    ]" />
                </div>
            </div>
            <span class="ml-2 text-sm text-dark" :class="{ 'opacity-50': disabled }">
                {{ option.label }}
            </span>
        </label>
    </div>
</template>

<script setup lang="ts">
interface RadioOption {
    label: string;
    value: string;
}

interface Props {
    modelValue?: string;
    options: RadioOption[];
    name: string;
    disabled?: boolean;
    direction?: 'horizontal' | 'vertical';
}

const props = withDefaults(defineProps<Props>(), {
    disabled: false,
    name: 'radio-group',
    direction: 'horizontal'
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: string): void;
}>();

const handleChange = (_event: Event, value: string) => {
    if (!props.disabled) {
        emit('update:modelValue', value);
    }
};
</script>