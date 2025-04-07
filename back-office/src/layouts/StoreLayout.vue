<template>
    <div class="flex h-dvh w-full overflow-hidden">
        <slot></slot>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue'
import { useRouter, useRoute } from 'vue-router';
import { storeToRefs } from 'pinia';

import { useSalonsStore } from '@/stores/salons';

const router = useRouter()
const route = useRoute()

const salonStore = useSalonsStore()
const { getterSalon } = storeToRefs(salonStore)

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
