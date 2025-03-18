<template>
  <div class="bg-white rounded-lg shadow-md p-4">
    <!-- Navigation et titre -->
    <div class="flex justify-between items-center mb-4">
      <button @click="goToPreviousPeriod" class="p-2 hover:bg-gray-100 rounded">
        ←
      </button>
      <h2 class="text-xl font-semibold">{{ formatMonthYear }}</h2>
      <button @click="goToNextPeriod" class="p-2 hover:bg-gray-100 rounded">
        →
      </button>
    </div>

    <!-- Modes de vue -->
    <div class="flex justify-center mb-4 space-x-2">
      <button v-for="mode in ['month', 'week', 'day']" :key="mode" @click="viewMode = mode as ViewMode" :class="[
        'px-4 py-2 rounded',
        viewMode === mode
          ? 'bg-blue-500 text-white'
          : 'bg-gray-200 text-gray-700'
      ]">
        {{ mode }}
      </button>
    </div>

    <!-- Jours de la semaine -->
    <div v-if="viewMode !== 'day'" class="grid grid-cols-7 text-center font-semibold text-gray-500 mb-2">
      <div>Lu</div>
      <div>Ma</div>
      <div>Me</div>
      <div>Je</div>
      <div>Ve</div>
      <div>Sa</div>
      <div>Di</div>
    </div>

    <!-- Grille des jours -->
    <div v-if="viewMode !== 'day'" class="grid grid-cols-7 gap-1 text-center">
      <button v-for="day in calendarDays" :key="day.toISOString()" @click="selectDate(day)" :class="[
        'p-2 rounded transition-colors',
        !isSameMonth(day, currentDate) ? 'text-gray-300' : 'text-gray-700',
        isDaySelected(day) ? 'bg-blue-500 text-white' : '',
        isDayInRange(day) ? 'bg-blue-200' : '',
        isDateDisabled(day) ? 'text-gray-300 line-through' : 'hover:bg-blue-100'
      ]">
        {{ formatDay(day) }}
      </button>
    </div>

    <!-- Vue jour -->
    <div v-else class="text-center">
      <div class="text-4xl font-bold mb-4">
        {{ format(currentDate, 'dd MMMM yyyy') }}
      </div>
      <button @click="selectDate(currentDate)" :class="[
        'px-6 py-3 rounded',
        isDaySelected(currentDate)
          ? 'bg-blue-500 text-white'
          : 'bg-blue-100 text-blue-700'
      ]">
        Sélectionner cette date
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, watch } from 'vue'
import { format, startOfMonth, endOfMonth, startOfWeek, endOfWeek, eachDayOfInterval, isSameMonth, isSameDay, addMonths, subMonths, addWeeks, subWeeks } from 'date-fns'

type ViewMode = 'month' | 'week' | 'day'
type SelectionMode = 'single' | 'range' | 'multiple'

interface Props {
  initialDate?: Date
  minDate?: Date
  maxDate?: Date
  viewMode?: ViewMode
  selectionMode?: SelectionMode
  disabledDates?: Date[]
}

const props = withDefaults(defineProps<Props>(), {
  initialDate: () => new Date(),
  viewMode: 'month',
  selectionMode: 'single',
  disabledDates: () => []
})

const emit = defineEmits<{
  (e: 'update:selectedDates', dates: Date[]): void
  (e: 'change', date: Date): void
}>()

// État du calendrier
const currentDate = ref(props.initialDate)
const selectedDates = ref<Date[]>([])
const viewMode = ref<ViewMode>(props.viewMode)

// Navigation entre vues
const goToPreviousPeriod = () => {
  switch (viewMode.value) {
    case 'month':
      currentDate.value = subMonths(currentDate.value, 1)
      break
    case 'week':
      currentDate.value = subWeeks(currentDate.value, 1)
      break
    case 'day':
      currentDate.value = new Date(currentDate.value.setDate(currentDate.value.getDate() - 1))
      break
  }
}

const goToNextPeriod = () => {
  switch (viewMode.value) {
    case 'month':
      currentDate.value = addMonths(currentDate.value, 1)
      break
    case 'week':
      currentDate.value = addWeeks(currentDate.value, 1)
      break
    case 'day':
      currentDate.value = new Date(currentDate.value.setDate(currentDate.value.getDate() + 1))
      break
  }
}

// Génération des jours du calendrier
const calendarDays = computed(() => {
  switch (viewMode.value) {
    case 'month':
      return generateMonthView(currentDate.value)
    case 'week':
      return generateWeekView(currentDate.value)
    case 'day':
      return [currentDate.value]
    default:
      return [currentDate.value]
  }
})

const generateMonthView = (date: Date) => {
  const start = startOfWeek(startOfMonth(date), { weekStartsOn: 1 })
  const end = endOfWeek(endOfMonth(date))
  return eachDayOfInterval({ start, end })
}

const generateWeekView = (date: Date) => {
  const start = startOfWeek(date, { weekStartsOn: 1 })
  const end = endOfWeek(date)
  return eachDayOfInterval({ start, end })
}

// Sélection de dates
const selectDate = (date: Date) => {
  if (isDateDisabled(date)) return

  switch (props.selectionMode) {
    case 'single':
      selectedDates.value = [date]
      break
    case 'multiple':
      const index = selectedDates.value.findIndex(d => isSameDay(d, date))
      if (index > -1) {
        selectedDates.value.splice(index, 1)
      } else {
        selectedDates.value.push(date)
      }
      break
    case 'range':
      if (selectedDates.value.length === 2) {
        selectedDates.value = [date]
      } else if (selectedDates.value.length === 1) {
        const [firstDate] = selectedDates.value
        selectedDates.value = firstDate <= date
          ? [firstDate, date]
          : [date, firstDate]
      } else {
        selectedDates.value = [date]
      }
      break
  }

  emit('update:selectedDates', selectedDates.value)
}

// Utilitaires
const isDateDisabled = (date: Date) => {
  return props.disabledDates.some(d => isSameDay(d, date))
}

const isDaySelected = (date: Date) => {
  return selectedDates.value.some(d => isSameDay(d, date))
}

const isDayInRange = (date: Date) => {
  if (props.selectionMode !== 'range' || selectedDates.value.length !== 2) return false

  const [start, end] = selectedDates.value
  return date > start && date < end
}

// Surveillance des changements
watch(selectedDates, (newDates) => {
  if (newDates.length > 0) {
    emit('change', newDates[0])
  }
})

// Formatage des dates
const formatDay = (date: Date) => format(date, 'd')
const formatMonthYear = computed(() => format(currentDate.value, 'MMMM yyyy'))
</script>
