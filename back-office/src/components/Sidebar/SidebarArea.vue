<template>
  <aside
    class="absolute left-0 top-0 z-9999 flex h-screen w-72.5 flex-col overflow-y-hidden bg-dark text-white duration-300 ease-linear lg:static lg:translate-x-0"
    :class="{
      'translate-x-0': sidebarStore.isSidebarOpen,
      '-translate-x-full': !sidebarStore.isSidebarOpen
    }" ref="target">

    <div class="flex items-center justify-between gap-2 px-6 py-5.5 lg:py-6.5">
      <CoelhoLogo size="sm" contrast="light" :isLink=true :href="router.resolve({ name: 'Home' }).href"
        class="mx-auto" />

      <CoelhoButton variant="secondary" :transparent="true" size="sm" type="button" class="block lg:hidden"
        @click="sidebarStore.isSidebarOpen = false">
        <CoelhoIcon :icon="ArrowLeftIcon" size="lg" />
      </CoelhoButton>
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
    </div>
  </aside>
</template>

<script setup lang="ts">
import { ref, type Component } from 'vue'
import { useRouter } from 'vue-router'
import { onClickOutside } from '@vueuse/core'

import { useSidebarStore } from '@/stores/sidebar'

import SidebarItem from './SidebarItem.vue'
import { CoelhoLogo, CoelhoButton, CoelhoIcon } from '@/components'
import { ArrowLeftIcon } from '@heroicons/vue/24/solid'

const router = useRouter()

const sidebarStore = useSidebarStore()

defineProps<{
  menuGroups: {
    name: string
    menuItems: {
      icon: Component
      label: string
      route: string
      children?: { label: string; route: string }[]
    }[]
  }[]
}>()

const target = ref(null)

onClickOutside(target, () => {
  sidebarStore.isSidebarOpen = false
})
</script>