<template>
    <li>
        <router-link :to="item.route"
            class="group relative flex items-center gap-2.5 rounded-sm py-2 px-4 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-graydark"
            @click.prevent="handleItemClick">
            <CoelhoIcon :icon="item.icon" />

            {{ item.label }}

            <CoelhoIcon v-if="getterSelectedCategory() === item.label" :icon="ChevronUpIcon" />
            <CoelhoIcon v-else="getterSelectedCategory() === item.label" :icon="ChevronDownIcon" />
        </router-link>

        <div class="translate transform overflow-hidden" v-show="getterSelectedCategory() === item.label">
            <SidebarDropdown v-if="item.children" :items="item.children" :currentPage="currentPage"
                :page="item.label" />
        </div>
    </li>
</template>

<script setup lang="ts">
import { useSidebarStore } from '@/stores/sidebar'
import { useRoute } from 'vue-router'
import SidebarDropdown from './SidebarDropdown.vue'

import type { SidebarItems } from '@/types'

import { CoelhoIcon } from '@/components'
import { ChevronUpIcon, ChevronDownIcon } from '@heroicons/vue/24/solid'

interface SidebarCategoryChild {
    label: SidebarItems
}

const { getterSelectedCategory, getterSelectedItem, mutationSelectedCategory } = useSidebarStore()

const props = defineProps(['item', 'index'])
const currentPage = useRoute().name

const handleItemClick = () => {
    const pageName = getterSelectedCategory === props.item.label ? '' : props.item.label
    mutationSelectedCategory(pageName)

    if (props.item.children) {
        return props.item.children.some((child: SidebarCategoryChild) => getterSelectedItem() === child.label)
    }
}
</script>