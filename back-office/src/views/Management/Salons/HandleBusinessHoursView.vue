<template>
    <div class="max-w-7xl mx-auto p-4" v-if="isReady">
        <h1 class="text-2xl font-semibold mb-4">
            {{ getterSalon?.name }} : {{ isEdit ? 'Editar' : 'Registar' }} horários de funcionamento
        </h1>

        <!-- Vue Calendrier -->
        <div class="mb-8 bg-white rounded-lg shadow-lg p-4">
            <FullCalendar :options="calendarOptions" />
        </div>

        <!-- Vue Liste -->
        <div class="bg-white rounded-lg shadow-lg p-4">
            <div class="grid md:grid-cols-3 gap-4">
                <div v-for="(schedule, dayIndex) in schedules" :key="schedule.day"
                    class="p-4 border border-stroke rounded-lg">
                    <div class="flex items-center justify-between mb-4">
                        <div class="flex items-center gap-4">
                            <span class="font-medium text-dark">{{ days[(schedule.day + 6) % 7] }}</span>
                            <label class="relative inline-flex items-center cursor-pointer">
                                <input type="checkbox" v-model="schedule.isOpen" class="sr-only peer">
                                <div class="w-11 h-6 bg-stroke peer-focus:outline-none peer-focus:ring-4 
                           peer-focus:ring-primary/30 rounded-full peer peer-checked:after:translate-x-full 
                           peer-checked:bg-primary after:content-[''] after:absolute after:top-[2px] 
                           after:left-[2px] after:bg-white after:rounded-full after:h-5 after:w-5 
                           after:transition-all"></div>
                            </label>
                        </div>
                    </div>

                    <div v-if="schedule.isOpen" class="space-y-4">
                        <!-- Liste des plages horaires -->
                        <div class="space-y-2">
                            <div v-for="(timeRange, rangeIndex) in schedule.timeRanges" :key="rangeIndex"
                                class="grid gap-4 relative items-center p-2 bg-whitten rounded-lg">
                                <div class="xl:flex gap-4">
                                    <div>
                                        <label class="block text-xs text-strokedark mb-1">Início</label>
                                        <input type="time" v-model="timeRange.start" class="w-full rounded-lg border border-stroke px-3 py-1 focus:border-primary 
                                  focus:outline-none">
                                    </div>
                                    <div>
                                        <label class="block text-xs text-strokedark mb-1">Fim</label>
                                        <input type="time" v-model="timeRange.end" class="w-full rounded-lg border border-stroke px-3 py-1 focus:border-primary 
                                  focus:outline-none">
                                    </div>
                                </div>
                                <DefaultButton value="Remover" type="danger" size="sm"
                                    @click="removeTimeRange(dayIndex, rangeIndex)" />
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <button @click="saveSchedules"
                    class="bg-primary text-white px-6 py-2 rounded-lg hover:bg-primary/90 transition">
                    Guardar horários
                </button>
            </div>
        </div>
    </div>
    <div v-else>
        <div class="flex justify-center items-center h-screen">
            <SpinLoader />
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted } from 'vue'
import { useRoute, useRouter } from 'vue-router'

import { api } from '@/api';
import { useSalonsStore } from '@/stores/salons';

import FullCalendar from '@fullcalendar/vue3'
import timeGridPlugin from '@fullcalendar/timegrid'
import interactionPlugin from '@fullcalendar/interaction'
import ptLocale from '@fullcalendar/core/locales/pt'
import type { CalendarOptions } from '@fullcalendar/core/index.js'
import DefaultButton from '@/components/Buttons/DefaultButton.vue'
import SpinLoader from '@/components/Loaders/SpinLoader.vue';
import { storeToRefs } from 'pinia';

interface TimeRange {
    start: string
    end: string
}

interface DaySchedule {
    day: number
    isOpen: boolean
    timeRanges: TimeRange[]
}

interface Event {
    title: string
    startTime?: string
    endTime?: string
    daysOfWeek?: number[]
    backgroundColor?: string
    display?: string
    allDay?: boolean
    textColor?: string
    classNames?: string[]
}

const route = useRoute()
const router = useRouter()

const salonsStore = useSalonsStore()
const { getterSalon } = storeToRefs(salonsStore)

const days = [
    'Segunda-feira',
    'Terça-feira',
    'Quarta-feira',
    'Quinta-feira',
    'Sexta-feira',
    'Sábado',
    'Domingo'
]

const isReady = ref(false)
const isEdit = ref(false)

const schedules = ref<DaySchedule[]>(days.map((_, index) => ({
    day: (index + 1) % 7,
    isOpen: false,
    timeRanges: []
})))

const calendarEvents = computed(() => {
    const events: Event[] = []

    schedules.value.forEach(schedule => {
        if (schedule.timeRanges.length > 0) {
            schedule.timeRanges.forEach(range => {
                events.push({
                    title: 'Aberto',
                    startTime: range.start,
                    endTime: range.end,
                    daysOfWeek: [schedule.day],
                    backgroundColor: '#3C50E0',
                    display: 'block'
                })
            })
        }
    })

    return events
})

// Configuration du calendrier
const calendarOptions = computed<CalendarOptions>(() => ({
    plugins: [timeGridPlugin, interactionPlugin],
    initialView: 'timeGridWeek',
    locale: ptLocale,
    firstDay: 1,
    slotMinTime: '06:00:00',
    slotMaxTime: '22:00:00',
    allDaySlot: false,
    headerToolbar: false,
    expandRows: true,
    selectable: true,
    selectMirror: true,
    events: calendarEvents.value,
    dayHeaderContent: (arg: any) => {
        return days[(arg.date.getDay() + 6) % 7]
    },
    select: handleCalendarSelect,
    height: 'auto',
    slotEventOverlap: false
}))

// Gestion des sélections dans le calendrier
const handleCalendarSelect = (selectInfo: any) => {
    const day = new Date(selectInfo.start).getDay()
    const startTime = selectInfo.start.toLocaleTimeString('pt-PT', {
        hour: '2-digit',
        minute: '2-digit'
    })
    const endTime = selectInfo.end.toLocaleTimeString('pt-PT', {
        hour: '2-digit',
        minute: '2-digit'
    })

    addTimeRange(day, startTime, endTime)
}

// Ajout d'une nouvelle plage horaire
const addTimeRange = (day: number, start: string, end: string) => {
    const schedule = schedules.value.find(s => s.day === day)
    if (schedule) {
        schedule.isOpen = true
        schedule.timeRanges.push({ start, end })
        // Tri des plages horaires par heure de début
        schedule.timeRanges.sort((a, b) => a.start.localeCompare(b.start))
    }
}

// Suppression d'une plage horaire
const removeTimeRange = (dayIndex: number, rangeIndex: number) => {
    const schedule = schedules.value[dayIndex]
    schedule.timeRanges.splice(rangeIndex, 1)
    // Si plus de plages horaires, on marque le jour comme fermé
    if (schedule.timeRanges.length === 0) {
        schedule.isOpen = false
    }
}

const saveSchedules = async () => {
    try {
        console.log('Horaires à sauvegarder:', schedules.value)
        await api().businessHours.create({ id: Array.isArray(route.params.id) ? route.params.id[0] : route.params.id, businessHoursRanges: schedules.value });
        // router.push({ name: 'GetSalon', params: { id: route.params.id } })
        router.push({ name: 'HandleForfait', params: { id: route.params.id } })
    } catch (error) {
        console.error('Erreur lors de la sauvegarde:', error)
    }
}

onMounted(async () => {
    await salonsStore.getSalon({ id: Array.isArray(route.params.id) ? Number(route.params.id[0]) : Number(route.params.id) });
    console.log("🚀 ~ onMounted ~ getterSalon:", getterSalon.value?.name)
    await salonsStore.getBusinessHours({ id: Array.isArray(route.params.id) ? Number(route.params.id[0]) : Number(route.params.id) });
    salonsStore.getterBusinessHours?.forEach((businessHour) => {
        addTimeRange(Number(businessHour.dayOfWeek), businessHour.startTime, businessHour.endTime)
    })
    isEdit.value = salonsStore.getterBusinessHours?.length ? true : false
    console.log("🚀 ~ onMounted ~ isEdit:", isEdit.value)
    isReady.value = true
})
</script>