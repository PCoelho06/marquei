<template>
    <div class="flex h-screen overflow-hidden">
        <SidebarArea :menuGroups />
        <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
            <HeaderArea />
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

import { managementSidebarMenuItems } from '@/composables/routing';

const menuGroups = ref([
    {
        name: 'MENU',
        menuItems: managementSidebarMenuItems
    },
    {
        name: 'DATA',
        menuItems: [
            {
                icon: 'report',
                label: 'Relatórios',
                route: '/',
            },
            {
                icon: 'finance',
                label: 'Financeiro',
                route: '/',
            },
        ]
    }
])
</script>
