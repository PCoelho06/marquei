<template>
    <div class="relative flex flex-1 flex-col overflow-y-auto overflow-x-hidden">
        <HeaderArea :goBackLink />
        <slot></slot>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router';
import { storeToRefs } from 'pinia';

import { useSalonsStore } from '@/stores/salons';

import HeaderArea from '@/components/Header/HeaderArea.vue'

const router = useRouter()
const route = useRoute()

const salonStore = useSalonsStore()
const { getterSalon } = storeToRefs(salonStore)

withDefaults(defineProps<{
    goBackLink?: boolean
}>(), {
    goBackLink: false
})

onMounted(() => {
    if (!getterSalon.value) {
        salonStore.getSalon({ id: Number(route.params.salonId) }).then(() => {
            if (!getterSalon.value) {
                router.push({ name: 'StoreNotFound' })
            }
        })
    }
})
</script>
