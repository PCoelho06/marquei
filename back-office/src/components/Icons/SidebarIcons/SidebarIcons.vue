<template>
    <DashboardIcon v-if="icon === 'dashboard'" />
    <ShopIcon v-if="icon === 'shop'" class="fill-current" />
    <CalendarIcon v-if="icon === 'calendar'" />
</template>

<script setup lang="ts">
import DashboardIcon from './DashboardIcon.vue';
import CalendarIcon from './CalendarIcon.vue';
import ShopIcon from '../ShopIcon.vue';

defineProps<{
    icon: string
}>()
</script>