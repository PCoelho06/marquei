<template>
  <aside
    class="absolute left-0 top-0 z-9999 flex h-screen w-72.5 flex-col overflow-y-hidden bg-dark text-white duration-300 ease-linear lg:static lg:translate-x-0"
    :class="{
      'translate-x-0': sidebarStore.isSidebarOpen,
      '-translate-x-full': !sidebarStore.isSidebarOpen
    }" ref="target">

    <div class="flex items-center justify-between gap-2 px-6 py-5.5 lg:py-6.5">
      <router-link to="/" class="text-4xl text-white font-semibold">
        💈 Marquei
      </router-link>

      <button class="block lg:hidden" @click="sidebarStore.isSidebarOpen = false">
        <svg class="fill-current" width="20" height="18" viewBox="0 0 20 18" fill="none"
          xmlns="http://www.w3.org/2000/svg">
          <path
            d="M19 8.175H2.98748L9.36248 1.6875C9.69998 1.35 9.69998 0.825 9.36248 0.4875C9.02498 0.15 8.49998 0.15 8.16248 0.4875L0.399976 8.3625C0.0624756 8.7 0.0624756 9.225 0.399976 9.5625L8.16248 17.4375C8.31248 17.5875 8.53748 17.7 8.76248 17.7C8.98748 17.7 9.17498 17.625 9.36248 17.475C9.69998 17.1375 9.69998 16.6125 9.36248 16.275L3.02498 9.8625H19C19.45 9.8625 19.825 9.4875 19.825 9.0375C19.825 8.55 19.45 8.175 19 8.175Z"
            fill="" />
        </svg>
      </button>
    </div>

    <div class="no-scrollbar flex flex-col overflow-y-auto duration-300 ease-linear">
      <nav class="mt-5 py-4 px-4 lg:mt-9 lg:px-6">
        <template v-for="menuGroup in menuGroups" :key="menuGroup.name">
          <div>
            <h3 class="mb-4 ml-4 text-sm font-medium text-bodydark2">{{ menuGroup.name }}</h3>

            <ul class="mb-6 flex flex-col gap-1.5">
              <SidebarItem v-for="(menuItem, index) in menuGroup.menuItems" :item="menuItem" :key="index"
                :index="index" />
            </ul>
          </div>
        </template>
      </nav>

      <div
        class="mx-auto mb-10 w-full max-w-60 rounded-sm border border-strokedark bg-boxdark py-6 px-4 text-center shadow-xl">
        <h3 class="mb-1 font-semibold text-white">Marquei Pro</h3>
        <p class="mb-4 text-xs">Aproveite todos os benefícios premium com a Marquei Pro !</p>
        <a href="https://tailadmin.com/pricing" target="_blank" rel="nofollow"
          class="flex items-center justify-center rounded-md bg-primary p-2 font-medium text-white hover:bg-opacity-90">
          Compre agora
        </a>
      </div>
    </div>
  </aside>
</template>

<script setup lang="ts">
import { useSidebarStore } from '@/stores/sidebar'
import { onClickOutside } from '@vueuse/core'
import { ref } from 'vue'
import SidebarItem from './SidebarItem.vue'

const target = ref(null)

const sidebarStore = useSidebarStore()

onClickOutside(target, () => {
  sidebarStore.isSidebarOpen = false
})

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
        label: 'Saloes',
        route: '#',
        children: [{ label: 'Registar', route: '/salons/create' }]
      },
      // {
      //     icon: 'CalendarIcon.vue',
      //     label: 'Agendamentos',
      //     route: '/calendar'
      // },
      // {
      //     icon: 'ResourcesIcon.vue',
      //     label: 'Recursos',
      //     route: '/resources',
      //     children: [
      //         { label: 'Empregados', route: '/resources/employees' },
      //         { label: 'Maquinas', route: '/resources/material' }
      //     ]
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