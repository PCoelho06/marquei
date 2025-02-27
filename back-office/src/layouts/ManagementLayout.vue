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

const menuGroups = ref([
    {
        name: 'MENU',
        menuItems: [
            {
                icon: 'dashboard',
                label: 'Painel',
                route: '/',
                // children: [{ label: 'eCommerce', route: '/' }]
            },
            {
                icon: 'shop',
                label: 'Salões',
                route: '/saloes',
                children: [
                    { label: 'Listar', route: '/saloes' },
                    { label: 'Registar', route: '/saloes/registar' },
                ]
            },
            // {
            {
                icon: 'resources',
                label: 'Recursos',
                route: '/recursos',
                children: [
                    { label: 'Empregados', route: '/recursos/empregados' },
                    { label: 'Maquinas', route: '/recursos/maquinas' }
                ]
            },
            //     icon: 'CalendarIcon.vue',
            //     label: 'Agendamentos',
            //     route: '/calendar'
            // },
            // {
            //     icon: 'ClientsIcon.vue',
            //     label: 'Clientes',
            //     route: '/clients'
            // },
            // {
            //     icon: 'ServicesIcon.vue',
            //     label: 'Serviços',
            //     route: '/services',
            //     children: [
            //         { label: 'Categorias', route: '/services/categories' },
            //         { label: 'Serviços', route: '/services/services' }
            //     ]
            // },
        ]
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
