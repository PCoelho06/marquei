<template>
    <li>
        <router-link :to="item.route"
            class="group relative flex items-center gap-2.5 rounded-sm py-2 px-4 font-medium text-bodydark1 duration-300 ease-in-out hover:bg-graydark"
            @click.prevent="handleItemClick">
            <CoelhoIcon :icon="item.icon" />

            {{ item.label }}
        </router-link>
    </li>
</template>

<script setup lang="ts">
import { useSidebarStore } from '@/stores/sidebar'

import { CoelhoIcon } from '@/components'

const { getterSelectedCategory, getterSelectedItem, mutationSelectedCategory } = useSidebarStore()

const props = defineProps(['item', 'index'])

const handleItemClick = () => {
    const pageName = getterSelectedCategory === props.item.label ? '' : props.item.label
    mutationSelectedCategory(pageName)
}
</script>