<template>
    <div class="flex flex-col">
        <label v-if="label" :for="id" class="block mb-2 text-sm font-medium text-dark">
            <CoelhoText weight="medium" size="sm">
                {{ label }}
            </CoelhoText>
        </label>
        <div class="flex items-center gap-2">
            <CoelhoInput v-model="modelValue.min" :id="'min-' + id" type="number" :size :disabled="disabled" :suffix />
            <span>-</span>
            <CoelhoInput v-model="modelValue.max" :id="'min-' + id" type="number" :size :disabled="disabled" :suffix />
        </div>
    </div>
</template>

<script setup lang="ts">
import { CoelhoInput, CoelhoText } from '..';

interface ModelValue {
    min: number;
    max: number;
}

interface Props {
    modelValue: ModelValue;
    id?: string;
    size?: 'sm' | 'md' | 'lg';
    label?: string;
    error?: string;
    helper?: string;
    disabled?: boolean;
    suffix?: string;
}

withDefaults(defineProps<Props>(), {
    size: 'md',
    disabled: false,
    id: `input-${Math.random().toString(36).substring(2, 11)}`,
    required: false,
    autocomplete: 'off',
});
</script>