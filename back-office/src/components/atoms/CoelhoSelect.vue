<template>
    <div class="relative">
        <label v-if="label" :for="id" class="block mb-2 text-sm font-medium text-dark">
            <CoelhoText weight="medium" size="sm">{{ label }}</CoelhoText>
        </label>

        <div class="relative">
            <CoelhoIcon v-if="leftIcon" :icon="leftIcon" size="xl" color="secondary"
                class="absolute left-2 top-1/2 transform -translate-y-1/2" />

            <input v-if="searchable" ref="searchInput" type="text" v-model="displayValue"
                class="w-full rounded-md border border-stroke bg-white px-3 py-2 pr-10 text-sm focus:border-primary focus:outline-none"
                :class="{ 'opacity-50 cursor-not-allowed': disabled, 'pl-13': leftIcon }" :placeholder="placeholder"
                @focus="handleSearchFocus" @input="handleSearchInput" @blur="handleBlur" :disabled="disabled" />

            <input v-else-if="editable" ref="editableInput" type="text" :value="modelValue"
                class="w-full rounded-md border border-stroke bg-white px-3 py-2 pr-10 text-sm focus:border-primary focus:outline-none"
                :class="{ 'opacity-50 cursor-not-allowed': disabled, 'pl-13': leftIcon }" :placeholder="placeholder"
                @focus="handleFocus" @input="handleInput" />

            <div v-else ref="selectTrigger"
                class="w-full rounded-md border border-stroke bg-white px-3 py-2 pr-10 text-sm cursor-pointer focus:border-primary"
                :class="{ 'opacity-50 cursor-not-allowed': disabled, 'pl-13': leftIcon }"
                @click="!disabled && toggleDropdown()" tabindex="0"
                @keydown.space.prevent="!disabled && toggleDropdown()"
                @keydown.enter.prevent="!disabled && toggleDropdown()" @blur="handleBlur">
                <span v-if="multiple && Array.isArray(selectedValues)">
                    <CoelhoText v-if="selectedValues.length === 0" size="sm" class="text-gray-500">{{ placeholder }}
                    </CoelhoText>
                    <div v-else class="flex flex-wrap gap-1">
                        <CoelhoBadge v-for="value in selectedValues" :key="value" size="sm" :closeable="true"
                            @close="removeValue(value)">
                            {{ getOptionLabel(value) }}
                        </CoelhoBadge>
                    </div>
                </span>
                <span v-else>
                    {{ selectedLabel || placeholder }}
                </span>
            </div>

            <CoelhoText v-if="suffix" size="sm" color="secondary"
                class="absolute right-2 top-1/2 transform -translate-y-1/2">
                {{ suffix }}
            </CoelhoText>
            <div v-else class="absolute right-3 top-1/2 -translate-y-1/3 pointer-events-none">
                <CoelhoIcon v-if="isOpen" :icon="ChevronUpIcon" color="secondary" />
                <CoelhoIcon v-else :icon="ChevronDownIcon" color="secondary" />
            </div>


            <div v-show="isOpen" ref="dropdown"
                class="absolute z-50 w-full mt-1 bg-white border border-stroke rounded-md shadow-lg max-h-60 overflow-auto">
                <template v-if="filteredOptions.length">
                    <div v-for="option in filteredOptions" :key="option.value"
                        @click="!disabled && selectOption(option)" class="px-3 py-2 cursor-pointer hover:bg-whitten"
                        :class="{
                            'bg-primary/10': isSelected(option.value),
                            'cursor-not-allowed opacity-50': disabled
                        }">
                        <div class="flex items-center">
                            <input v-if="multiple" type="checkbox" :checked="isSelected(option.value)"
                                class="mr-2 rounded border-stroke text-primary focus:ring-primary" />
                            {{ option.label }}
                        </div>
                    </div>
                </template>
                <div v-else class="px-3 py-2 text-gray-500">
                    <CoelhoText size="sm">Sem resultados</CoelhoText>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';

import type { Component } from 'vue';
import type { SelectOption } from '../types/tables';

import { CoelhoBadge, CoelhoIcon, CoelhoText } from '@/components';
import { ChevronDownIcon, ChevronUpIcon } from '@heroicons/vue/24/solid';

interface Props {
    modelValue: number | number[] | string | string[];
    options: SelectOption[];
    label?: string;
    placeholder?: string;
    disabled?: boolean;
    multiple?: boolean;
    searchable?: boolean;
    id?: string;
    leftIcon?: Component;
    suffix?: string;
    editable?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    disabled: false,
    multiple: false,
    searchable: false,
    placeholder: 'Selecionar',
    id: computed(() => `select-${Math.random().toString(36).slice(2, 11)}`).value,
    editable: false,
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: number | string | (number | string)[]): void;
}>();

const isOpen = ref(false);
const searchQuery = ref('');
const selectTrigger = ref<HTMLElement | null>(null);
const searchInput = ref<HTMLElement | null>(null);
const dropdown = ref<HTMLElement | null>(null);
const editableInput = ref<HTMLInputElement | null>(null);
const displayValue = ref('');

const filteredOptions = computed(() => {
    if (!searchQuery.value) return props.options;
    const query = searchQuery.value.toLowerCase();
    return props.options.filter(option =>
        option.label.toLowerCase().includes(query)
    );
});

const selectedValues = computed(() => {
    if (props.multiple) {
        return Array.isArray(props.modelValue) ? props.modelValue : [];
    }
    return props.modelValue ? props.modelValue : [];
});

const selectedLabel = computed(() => {
    if (!props.modelValue) return '';
    const option = props.options.find(opt => opt.value === props.modelValue);
    return option ? option.label : '';
});

const toggleDropdown = () => {
    isOpen.value = !isOpen.value;
    if (isOpen.value && props.searchable) {
        setTimeout(() => searchInput.value?.focus(), 0);
    }
};

const handleFocus = () => {
    if (!props.disabled) {
        isOpen.value = true;
    }
};

const handleBlur = (event: FocusEvent) => {
    if (dropdown.value?.contains(event.relatedTarget as Node)) {
        return;
    }
    setTimeout(() => {
        isOpen.value = false;
        searchQuery.value = '';
    }, 200);
};

const handleSearchFocus = () => {
    displayValue.value = searchQuery.value;
    handleFocus();
};

const handleSearchInput = (event: Event) => {
    const input = event.target as HTMLInputElement;
    searchQuery.value = input.value;
};

const selectOption = (option: SelectOption) => {
    if (props.multiple) {
        const values = Array.isArray(props.modelValue) ? [...props.modelValue] : [];
        const index = values.indexOf(option.value);
        if (index === -1) {
            values.push(option.value);
        } else {
            values.splice(index, 1);
        }
        emit('update:modelValue', values);
    } else {
        emit('update:modelValue', option.value);
        if (props.searchable) {
            displayValue.value = option.label;
            searchQuery.value = '';
        }
        isOpen.value = false;
    }
};

const removeValue = (value: string | number) => {
    if (props.multiple) {
        const values = (props.modelValue as string[]).filter(v => v !== value);
        emit('update:modelValue', values);
    }
};

const getOptionLabel = (value: string | number) => {
    const option = props.options.find(opt => opt.value === value);
    return option ? option.label : value;
};

const isSelected = (value: string | number) => {
    if (props.multiple) {
        return Array.isArray(props.modelValue) && (props.modelValue as string[]).includes(value.toString());
    }
    return props.modelValue === value;
};

const handleClickOutside = (event: MouseEvent) => {
    if (
        !selectTrigger.value?.contains(event.target as Node) &&
        !dropdown.value?.contains(event.target as Node) &&
        !searchInput.value?.contains(event.target as Node) &&
        !editableInput.value?.contains(event.target as Node)
    ) {
        isOpen.value = false;
        searchQuery.value = '';
    }
};

onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});

watch(isOpen, (newValue) => {
    if (!newValue) {
        searchQuery.value = '';
    }
});

watch(() => props.modelValue, (newValue) => {
    if (props.searchable && !props.multiple && newValue) {
        const option = props.options.find(opt => opt.value == newValue);
        displayValue.value = option ? option.label : '';
    }
}, { immediate: true });

const handleInput = (event: Event) => {
    const input = event.target as HTMLInputElement;
    emit('update:modelValue', input.value);
};
</script>