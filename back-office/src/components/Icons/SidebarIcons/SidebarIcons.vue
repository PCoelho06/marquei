<template>
    <DashboardIcon v-if="icon === 'dashboard'" />
    <CalendarIcon v-if="icon === 'calendar'" />
</template>

<script setup lang="ts">
import DashboardIcon from './DashboardIcon.vue';
import CalendarIcon from './CalendarIcon.vue';

defineProps<{
    icon: string
}>()
</script>