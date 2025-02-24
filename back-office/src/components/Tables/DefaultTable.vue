<template>
    <table class="table-fixed w-full">
        <thead>
            <tr>
                <th v-for="column in columns" :key="column.key"
                    class="px-6 py-3 text-left cursor-pointer hover:bg-gray-200" @click="handleSort(column.key)">
                    {{ column.label }}
                    <SortDownIcon class="ml-1" size="sm"
                        v-if="sortConfig.key === column.key && sortConfig.direction === 'desc'" />
                    <SortUpIcon class="ml-1" size="sm"
                        v-if="sortConfig.key === column.key && sortConfig.direction === 'asc'" />
                    <SortIcon class="ml-1" size="sm" v-if="sortConfig.key !== column.key" />
                </th>
            </tr>
        </thead>
        <tbody>
            <tr v-for="item in items" :key="item.id" class="border-b hover:bg-gray-50">
                <td v-for="column in columns" :key="column.key" class="px-6 py-4">
                    {{ item[column.key] }}
                </td>
            </tr>
        </tbody>
    </table>
</template>

<script setup lang="ts">
import { ref } from 'vue'

import type { Props, SortConfig } from '../types/tables';

import SortIcon from '../Icons/SortIcon.vue';
import SortDownIcon from '../Icons/SortDownIcon.vue';
import SortUpIcon from '../Icons/SortUpIcon.vue';

const props = defineProps<Props<any>>()

const sortConfig = ref<SortConfig>({
    key: props.columns[0].key,
    direction: 'asc'
})

const handleSort = (key: string) => {
    if (sortConfig.value.key === key) {
        sortConfig.value.direction = sortConfig.value.direction === 'asc' ? 'desc' : 'asc'
    } else {
        sortConfig.value.key = key
        sortConfig.value.direction = 'asc'
    }
}
</script>