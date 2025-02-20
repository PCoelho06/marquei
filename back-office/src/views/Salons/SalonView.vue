<template>
    <div class="container mx-auto" v-if="isReady">
        <h1 class="text-2xl font-semibold">Salão {{ getterSalon?.name }}</h1>
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useRoute } from 'vue-router';
import { storeToRefs } from 'pinia';

import { useSalonsStore } from '@/stores/salons';
import DefaultCard from '@/components/Cards/DefaultCard.vue';
import DefaultButton from '@/components/Buttons/DefaultButton.vue';

const route = useRoute();

const salonsStore = useSalonsStore();
const { getterSalon } = storeToRefs(salonsStore);

const isReady = ref(false);

onMounted(() => {
    salonsStore.getSalon({ id: Number(route.params.id) });
    isReady.value = true;
});
</script>