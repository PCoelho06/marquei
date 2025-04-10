<template>
    <CoelhoInput type="text" v-model="modelValue" :label :leftIcon="CalendarDaysIcon" @click="togglePicker" :error />

    <div v-if="isOpen" class="absolute z-9999 mt-10 w-1/2 bg-white border border-gray-300 rounded shadow-lg p-4"
        ref="target">
        <div class="grid grid-cols-7 gap-1 text-center mb-4">
            <div class="col-span-7 flex justify-between items-center mb-2">
                <CoelhoButton :icon="ChevronLeftIcon" :transparent="true" variant="secondary" @click="prevMonth" />
                <span class="font-semibold">{{ formatters.capitalize(monthName) }} {{ currentYear }}</span>
                <CoelhoButton :icon="ChevronRightIcon" :transparent="true" variant="secondary" @click="nextMonth" />
            </div>
            <template v-for="day in weekDays" :key="day">
                <div class="text-sm font-medium text-gray-400">{{ day }}</div>
            </template>
            <template v-for="n in firstDayOffset" :key="'previous-offset-' + n">
                <button class="w-8 h-8 rounded text-gray-200">
                    {{ daysInPreviousMonth - firstDayOffset + n }}
                </button>
            </template>
            <template v-for="day in daysInMonth" :key="'day-' + day">
                <button class="w-8 h-8 rounded hover:bg-blue-100 cursor-pointer"
                    :class="{ 'bg-blue-500 text-white': isSelectedDay(day) }" @click="selectDate(day)">
                    {{ day }}
                </button>
            </template>
            <template v-for="n in lastDayOffset" :key="'next-offset-' + n">
                <button class="w-8 h-8 rounded text-gray-200">
                    {{ n }}
                </button>
            </template>
        </div>

        <div class="flex justify-between items-center space-x-4">
            <CoelhoSelect v-model="selectedHour"
                :options="Array.from({ length: 24 }, (_, i) => ({ label: pad(i), value: i }))" :label="'Hora'"
                suffix="h" :editable="true" @update:model-value="confirmSelection" />
            <CoelhoSelect v-model="selectedMinute"
                :options="Array.from({ length: 4 }, (_, i) => ({ label: pad(i * 15), value: i * 15 }))"
                :label="'Minuto'" suffix="min" :editable="true" @update:model-value="confirmSelection" />
        </div>

        <div class="flex justify-center mt-4 space-x-2">
            <CoelhoButton size="sm" :icon="XMarkIcon" :outlined="true" variant="danger" @click="clearSelection">Apagar
            </CoelhoButton>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, watch, useTemplateRef } from 'vue'
import { onClickOutside } from '@vueuse/core';

import { formatters } from '@/utils'

import { CoelhoInput, CoelhoButton, CoelhoSelect } from '@/components'
import { CalendarDaysIcon, ChevronLeftIcon, ChevronRightIcon, ClockIcon, XMarkIcon } from '@heroicons/vue/24/solid';

const props = withDefaults(defineProps<{
    initialValue?: string
    label?: string
    error?: string
}>(), {
    initialValue: '',
})

const emit = defineEmits<{
    (e: 'update:modelValue', value: string | null): void
}>()

const target = useTemplateRef('target')
const isOpen = ref<boolean>(false)
const modelValue = ref<string>(props.initialValue)

const weekDays = ['Dom', 'Seg', 'Ter', 'Qua', 'Qui', 'Sex', 'Sab']

const currentMonth = ref(new Date().getMonth())
const currentYear = ref(new Date().getFullYear())
const selectedDay = ref<number | null>(null)
const selectedHour = ref(0)
const selectedMinute = ref(0)

watch(
    () => props.initialValue,
    (val) => {
        if (val) {
            const [datePart, timePart] = val.split(', ')
            const [day, month, year] = datePart.split('/').map(Number)
            const [hour, minute] = timePart.split(':').map(Number)

            currentMonth.value = (month ?? 1) - 1
            currentYear.value = year ?? new Date().getFullYear()
            selectedDay.value = day ?? null
            selectedHour.value = hour ?? 0
            selectedMinute.value = minute ?? 0
        }
    },
    { immediate: true }
)

const togglePicker = () => {
    isOpen.value = !isOpen.value
}

const monthName = computed(() =>
    new Date(currentYear.value, currentMonth.value).toLocaleDateString('pt-PT', {
        month: 'long',
    })
)

const daysInMonth = computed(() => {
    return new Date(currentYear.value, currentMonth.value + 1, 0).getDate()
})

const daysInPreviousMonth = computed(() => {
    return new Date(currentYear.value, currentMonth.value, 0).getDate()
})

const firstDayOffset = computed(() => {
    return new Date(currentYear.value, currentMonth.value, 1).getDay()
})

const lastDayOffset = computed(() => {
    return 6 - new Date(currentYear.value, currentMonth.value + 1, 0).getDay()
})

function prevMonth() {
    if (currentMonth.value === 0) {
        currentMonth.value = 11
        currentYear.value--
    } else {
        currentMonth.value--
    }
}

function nextMonth() {
    if (currentMonth.value === 11) {
        currentMonth.value = 0
        currentYear.value++
    } else {
        currentMonth.value++
    }
}

function selectDate(day: number) {
    selectedDay.value = day
    confirmSelection()
}

function isSelectedDay(day: number): boolean {
    return selectedDay.value === day
}

function pad(n: number): string {
    return n.toString().padStart(2, '0')
}

function confirmSelection() {
    if (selectedDay.value !== null) {
        const selectedDate = new Date(currentYear.value, currentMonth.value + 1, selectedDay.value, selectedHour.value, selectedMinute.value)
        // const str = `${pad(selectedDay.value)}/${pad(currentMonth.value + 1)}/${currentYear.value} ${pad(selectedHour.value)}:${pad(selectedMinute.value)}`
        modelValue.value = formatters.formatDateTimeForm(selectedDate)
    }
}

onClickOutside(target, () => {
    isOpen.value = false
})

const clearSelection = () => {
    selectedDay.value = null
    selectedHour.value = 0
    selectedMinute.value = 0
    modelValue.value = ''
}
</script>