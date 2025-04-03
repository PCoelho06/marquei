<template>
    <ManagementLayout :goBackLink="true">
        <div class="mx-auto" v-if="isReady">
            <CoelhoHeading :level="1" size="sm">
                {{ getterResource?.name }} : {{ isEdit ? 'Editar' : 'Registar' }} horários de disponibilidade
            </CoelhoHeading>

            <CoelhoCard size="full">
                <FullCalendar :options="calendarOptions" />
                <template #footer>
                    <div class="flex justify-end">
                        <CoelhoButton :icon="BookmarkSquareIcon" @click="saveSchedules">
                            {{ isEdit ? 'Guardar' : 'Registar' }}
                        </CoelhoButton>
                    </div>
                </template>
            </CoelhoCard>

        </div>
        <div v-else>
            <div class="flex justify-center items-center h-screen">
                <CoelhoSpinner />
            </div>
        </div>
    </ManagementLayout>
</template>

<script setup lang="ts">
import { onMounted, computed, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { storeToRefs } from 'pinia'

import { api } from '@/api'
import { days } from '@/views/commons/composables/constants/dates'

import FullCalendar from '@fullcalendar/vue3'
import timeGridPlugin from '@fullcalendar/timegrid'
import interactionPlugin from '@fullcalendar/interaction'
import ptLocale from '@fullcalendar/core/locales/pt'

import ManagementLayout from '@/layouts/ManagementLayout.vue';

import { CoelhoCard, CoelhoHeading, CoelhoSpinner, CoelhoButton, CoelhoLink, CoelhoIcon } from '@/components'

import { useSalonsStore } from '@/stores/salons'
import { useResourcesStore } from '@/stores/resources'

import type { CalendarOptions } from '@fullcalendar/core'
import type { Event } from '@/types'
import type { AvailabilityRanges } from '@/types/resource-availabilities'
import type { BusinessHour } from '@/types/business-hours'
import { BookmarkSquareIcon, ArrowLeftIcon } from '@heroicons/vue/24/solid';

const route = useRoute()
const router = useRouter()

const salonsStore = useSalonsStore()
const resourcesStore = useResourcesStore()
const { getterSalon } = storeToRefs(salonsStore)
const { getterResource } = storeToRefs(resourcesStore)

const isEdit = ref<boolean>(false)
const isReady = ref<boolean>(false)

const selectionActive = ref(false);

const schedules = ref<AvailabilityRanges[]>(days.map((_, index) => ({
    dayOfWeek: (index + 1) % 7,
    isAvailable: false,
    timeRanges: []
})))

const businessHoursConstructor = () => {
    const businessHours: BusinessHour[] = []
    if (!getterSalon.value || !getterSalon.value.businessHours) {
        return [
            { daysOfWeek: [1], startTime: '', endTime: '' },
            { daysOfWeek: [2], startTime: '', endTime: '' },
            { daysOfWeek: [3], startTime: '', endTime: '' },
            { daysOfWeek: [4], startTime: '', endTime: '' },
            { daysOfWeek: [5], startTime: '', endTime: '' },
            { daysOfWeek: [6], startTime: '', endTime: '' },
            { daysOfWeek: [0], startTime: '', endTime: '' },
        ]
    }

    for (const businessHour of getterSalon.value.businessHours) {
        businessHours.push({
            daysOfWeek: [Number(businessHour.dayOfWeek)],
            startTime: businessHour.startTime,
            endTime: businessHour.endTime,
        })
    }
    return businessHours
}

const calendarEvents = computed(() => {
    const events: Event[] = []

    schedules.value.forEach(schedule => {
        if (schedule.timeRanges.length > 0) {
            schedule.timeRanges.forEach(range => {
                events.push({
                    title: 'Disponivel',
                    startTime: range.start,
                    endTime: range.end,
                    daysOfWeek: [schedule.dayOfWeek],
                    backgroundColor: '#3C50E0',
                    display: 'block'
                })
            })
        }
    })

    return events
})

const handleCalendarSelect = (selectInfo: any) => {
    if (!selectionActive.value) {
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
        selectionActive.value = true;
    }
}

const addTimeRange = (day: number, start: string, end: string) => {
    const schedule = schedules.value.find(s => s.dayOfWeek === day)
    if (schedule) {
        schedule.isAvailable = true
        schedule.timeRanges.push({ start, end })
        schedule.timeRanges.sort((a, b) => a.start.localeCompare(b.start))
    }
}

const editTimeRange = (event: any) => {
    const oldDay = event.oldEvent.start.getDay()
    const day = event.event.start.getDay()
    const oldStart = event.oldEvent.start.toLocaleTimeString('pt-PT', {
        hour: '2-digit',
        minute: '2-digit'
    })
    const start = event.event.start.toLocaleTimeString('pt-PT', {
        hour: '2-digit',
        minute: '2-digit'
    })
    const oldEnd = event.oldEvent.end.toLocaleTimeString('pt-PT', {
        hour: '2-digit',
        minute: '2-digit'
    })
    const end = event.event.end.toLocaleTimeString('pt-PT', {
        hour: '2-digit',
        minute: '2-digit'
    })

    if (oldDay !== day) {
        const oldSchedule = schedules.value.find(s => s.dayOfWeek === oldDay)
        if (oldSchedule) {
            const timeRangeIndex = oldSchedule.timeRanges.findIndex(tr => tr.start === oldStart && tr.end === oldEnd)
            if (timeRangeIndex !== -1) {
                oldSchedule.timeRanges.splice(timeRangeIndex, 1)

                if (oldSchedule.timeRanges.length === 0) {
                    oldSchedule.isAvailable = false
                }
            }
        }

        addTimeRange(day, start, end)
        return
    }

    const schedule = schedules.value.find(s => s.dayOfWeek === day)
    if (schedule) {
        const timeRange = schedule.timeRanges.find(tr => tr.start === oldStart && tr.end === oldEnd)
        if (timeRange) {
            timeRange.start = start
            timeRange.end = end
        }
    }
}

const calendarOptions = computed<CalendarOptions>(() => ({
    plugins: [timeGridPlugin, interactionPlugin],
    initialView: 'timeGridWeek',
    locale: ptLocale,
    firstDay: 1,
    slotMinTime: '06:00:00',
    slotMaxTime: '22:00:00',
    businessHours: businessHoursConstructor(),
    allDaySlot: false,
    headerToolbar: false,
    expandRows: true,
    selectable: true,
    selectOverlap: false,
    eventOverlap: false,
    editable: true,
    selectMirror: true,
    events: calendarEvents.value,
    dayHeaderContent: (arg: any) => {
        return days[(arg.date.getDay() + 6) % 7]
    },
    select: handleCalendarSelect,
    height: 'auto',
    slotEventOverlap: false,
    selectAllow: (selectInfo) => {
        if (selectionActive.value) {
            selectionActive.value = false
            return false
        }
        const businessHour = businessHoursConstructor().find((businessHour) => {
            return businessHour.daysOfWeek[0] === selectInfo.start.getDay()
        })
        const startTime = selectInfo.start.toLocaleTimeString('pt-PT', {
            hour: '2-digit',
            minute: '2-digit'
        })
        const endTime = selectInfo.end.toLocaleTimeString('pt-PT', {
            hour: '2-digit',
            minute: '2-digit'
        })
        if (startTime < (businessHour?.startTime || '24:00') || endTime > (businessHour?.endTime || '00:00')) {
            return false
        }
        return true
    },
    eventDrop: editTimeRange,
    eventResize: editTimeRange,
}))

const saveSchedules = async () => {
    try {
        console.log("🚀 ~ saveSchedules ~ schedules.value:", schedules.value)
        await api().resourceAvailabilities.manage({ id: route.params.id.toString(), availabilities: schedules.value });
        router.push({ name: 'GetResource', params: { id: route.params.id } })
    } catch (error) {
        console.error('Erreur lors de la sauvegarde:', error)
    }
}

onMounted(async () => {
    await resourcesStore.getResource({ id: Number(route.params.id) })
    getterResource.value?.resourceAvailabilities?.forEach((availability) => {
        addTimeRange(Number(availability.dayOfWeek), availability.startTime, availability.endTime)
    })
    isEdit.value = getterResource.value?.resourceAvailabilities?.length ? true : false
    isReady.value = true
})
</script>