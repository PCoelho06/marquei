<template>
    <div class="flex h-screen overflow-hidden">
        <SidebarArea :menuGroups />
        <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
            <HeaderArea :goBackLink />
            <main>
                <div class="mx-auto max-w-screen-2xl p-4 md:p-6 2xl:p-10">
                    <slot></slot>
                </div>
            </main>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'

import HeaderArea from '@/components/Header/HeaderArea.vue'
import SidebarArea from '@/components/Sidebar/SidebarArea.vue'

import { DocumentChartBarIcon, BanknotesIcon } from '@heroicons/vue/24/solid';

import { managementSidebarMenuItems } from '@/composables/routing';

withDefaults(defineProps<{
    goBackLink?: boolean
}>(), {
    goBackLink: false
})

const menuGroups = ref([
    {
        name: 'MENU',
        menuItems: managementSidebarMenuItems
    },
    {
        name: 'DATA',
        menuItems: [
            {
                icon: DocumentChartBarIcon,
                label: 'Relatórios',
                route: '/',
            },
            {
                icon: BanknotesIcon,
                label: 'Financeiro',
                route: '/',
            },
        ]
    }
])
</script>
