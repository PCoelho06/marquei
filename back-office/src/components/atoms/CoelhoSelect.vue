<template>
    <div class="relative">
        <!-- Label -->
        <label v-if="label" :for="id" class="block mb-2 text-sm font-medium text-dark">
            {{ label }}
        </label>

        <!-- Select Container -->
        <div class="relative">
            <!-- Search Input (for searchable variant) -->
            <input v-if="searchable" ref="searchInput" type="text" v-model="displayValue"
                class="w-full rounded-md border border-stroke bg-white px-3 py-2 pr-10 text-sm focus:border-primary focus:outline-none"
                :class="{ 'opacity-50 cursor-not-allowed': disabled }" :placeholder="placeholder"
                @focus="handleSearchFocus" @input="handleSearchInput" @blur="handleBlur" :disabled="disabled" />

            <!-- Selected Value Display (for non-searchable variant) -->
            <div v-else ref="selectTrigger"
                class="w-full rounded-md border border-stroke bg-white px-3 py-2 pr-10 text-sm cursor-pointer focus:border-primary"
                :class="{ 'opacity-50 cursor-not-allowed': disabled }" @click="!disabled && toggleDropdown()"
                tabindex="0" @keydown.space.prevent="!disabled && toggleDropdown()"
                @keydown.enter.prevent="!disabled && toggleDropdown()" @blur="handleBlur">
                <span v-if="multiple">
                    <span v-if="selectedValues.length === 0">{{ placeholder }}</span>
                    <div v-else class="flex flex-wrap gap-1">
                        <span v-for="value in selectedValues" :key="value"
                            class="inline-flex items-center bg-primary/10 px-2 py-0.5 rounded">
                            {{ getOptionLabel(value) }}
                            <button @click.stop="removeValue(value)" class="ml-1 text-primary hover:text-primary/80">
                                <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </span>
                    </div>
                </span>
                <span v-else>
                    {{ selectedLabel || placeholder }}
                </span>
            </div>

            <!-- Dropdown Arrow -->
            <div class="absolute right-3 top-1/2 -translate-y-1/2 pointer-events-none">
                <svg class="w-4 h-4 text-gray-400" :class="{ 'transform rotate-180': isOpen }" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
            </div>

            <!-- Options Dropdown -->
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
                    Aucun résultat
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, watch, onMounted, onUnmounted } from 'vue';

interface SelectOption {
    label: string;
    value: string;
}

interface Props {
    modelValue: string | string[];
    options: SelectOption[];
    label?: string;
    placeholder?: string;
    disabled?: boolean;
    multiple?: boolean;
    searchable?: boolean;
    id?: string;
}

const props = withDefaults(defineProps<Props>(), {
    disabled: false,
    multiple: false,
    searchable: false,
    placeholder: 'Sélectionner...',
    id: computed(() => `select-${Math.random().toString(36).slice(2, 11)}`).value,
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: string | string[]): void;
}>();

// State
const isOpen = ref(false);
const searchQuery = ref('');
const selectTrigger = ref<HTMLElement | null>(null);
const searchInput = ref<HTMLElement | null>(null);
const dropdown = ref<HTMLElement | null>(null);
const displayValue = ref('');

// Computed
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

// Methods
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
    // Vérifie si le focus est passé à un élément du dropdown
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

const removeValue = (value: string) => {
    if (props.multiple) {
        const values = (props.modelValue as string[]).filter(v => v !== value);
        emit('update:modelValue', values);
    }
};

const getOptionLabel = (value: string) => {
    const option = props.options.find(opt => opt.value === value);
    return option ? option.label : value;
};

const isSelected = (value: string) => {
    if (props.multiple) {
        return Array.isArray(props.modelValue) && props.modelValue.includes(value);
    }
    return props.modelValue === value;
};

// Click outside handler
const handleClickOutside = (event: MouseEvent) => {
    if (
        !selectTrigger.value?.contains(event.target as Node) &&
        !dropdown.value?.contains(event.target as Node) &&
        !searchInput.value?.contains(event.target as Node)
    ) {
        isOpen.value = false;
        searchQuery.value = '';
    }
};

// Lifecycle
onMounted(() => {
    document.addEventListener('click', handleClickOutside);
});

onUnmounted(() => {
    document.removeEventListener('click', handleClickOutside);
});

// Reset search query when dropdown closes
watch(isOpen, (newValue) => {
    if (!newValue) {
        searchQuery.value = '';
    }
});

// Ajout d'un watch pour mettre à jour displayValue
watch(() => props.modelValue, (newValue) => {
    if (props.searchable && !props.multiple && newValue) {
        const option = props.options.find(opt => opt.value === newValue);
        displayValue.value = option ? option.label : '';
    }
}, { immediate: true });
</script>