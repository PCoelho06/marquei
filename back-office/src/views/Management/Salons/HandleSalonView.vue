<template>
    <CenteredLayout>
        <div class="container mx-auto w-8/10">
            <h1 class="text-2xl font-semibold mb-4">
                {{ isEdit ? 'Editar o salão' : 'Registar um salão' }}
            </h1>
            <DefaultCard cardTitle="Informações do salão">
                <template #default>
                    <SalonForm />
                </template>
            </DefaultCard>
        </div>
    </CenteredLayout>
</template>

<script setup lang="ts">
import { onMounted } from 'vue';
import { useRoute } from 'vue-router';

import { useSalonsStore } from '@/stores/salons';

import DefaultCard from '@/components/Cards/DefaultCard.vue';
import CenteredLayout from '@/layouts/CenteredLayout.vue';
import SalonForm from './lib/SalonForm.vue';

const route = useRoute();

const salonsStore = useSalonsStore();

const isEdit = route.params.id ? true : false;

onMounted(() => {
    if (isEdit) {
        salonsStore.getSalon({ id: Number(route.params.id) });
    }

});
</script>