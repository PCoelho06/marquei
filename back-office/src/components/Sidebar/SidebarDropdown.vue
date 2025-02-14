<template>
    <ul class="mt-4 mb-5.5 flex flex-col gap-2.5 pl-6">
        <template v-for="(childItem, index) in items" :key="index">
            <li>
                <router-link :to="childItem.route" @click="handleItemClick(index)"
                    class="group relative flex items-center gap-2.5 rounded-md px-4 font-medium text-bodydark2 duration-300 ease-in-out hover:text-white"
                    :class="{
                        '!text-white': childItem.label === getterSelectedItem
                    }">
                    {{ childItem.label }}
                </router-link>
            </li>
        </template>
    </ul>
</template>

<script setup lang="ts">
import { useSidebarStore } from '@/stores/sidebar'

const { getterSelectedItem, mutationSelectedItem } = useSidebarStore()

const props = defineProps(['items', 'page'])

const handleItemClick = (index: number) => {
    const pageName = getterSelectedItem === props.items[index].label ? '' : props.items[index].label
    mutationSelectedItem(pageName)
}
</script>