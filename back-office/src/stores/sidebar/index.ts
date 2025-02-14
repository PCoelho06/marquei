import { defineStore } from 'pinia'
import { ref } from 'vue'

import type { SidebarCategories, SidebarItems } from '@/types'

export const useSidebarStore = defineStore('sidebar', () => {
  const isSidebarOpen = ref<boolean>(false)
  const selectedItem = ref<SidebarItems | null>(null)
  const selectedCategory = ref<SidebarCategories>('Painel')

  const getterSelectedItem = () => selectedItem.value
  const getterSelectedCategory = () => selectedCategory.value

  const mutationSelectedItem = (menu: SidebarItems) => {
    selectedItem.value = menu
  }
  const mutationSelectedCategory = (category: SidebarCategories) => {
    selectedCategory.value = category
  }

  const toggleSidebar = () => {
    isSidebarOpen.value = !isSidebarOpen.value
  }

  const resetSelectedItem = () => {
    selectedItem.value = null
  }
  const resetSelectedCategory = () => {
    selectedCategory.value = 'Painel'
  }

  return {
    isSidebarOpen,
    getterSelectedItem,
    getterSelectedCategory,
    mutationSelectedItem,
    mutationSelectedCategory,
    toggleSidebar,
    resetSelectedItem,
    resetSelectedCategory,
  }
})
