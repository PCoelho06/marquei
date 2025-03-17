<template>
    <CenteredLayout>
        <div class="container mx-auto w-8/10">
            <CoelhoLink :to="router.resolve({ name: 'Resources' }).href" class="flex items-center gap-2 mb-4">
                <CoelhoIcon :icon="ArrowLeftIcon" />
                Voltar
            </CoelhoLink>
            <h1 class="text-2xl font-semibold mb-4">
                {{ isEdit ? 'Editar a ficha do Recurso' : 'Registar um recurso' }}
            </h1>
            <CoelhoCard size="full">
                <ResourceForm />
            </CoelhoCard>
        </div>
    </CenteredLayout>
</template>

<script setup lang="ts">
import { ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';

import ResourceForm from './lib/ResourceForm.vue';

import CenteredLayout from '@/layouts/CenteredLayout.vue';
import { CoelhoLink, CoelhoIcon, CoelhoCard } from '@coelhoui';
import { ArrowLeftIcon } from '@heroicons/vue/24/solid';

const route = useRoute();
const router = useRouter();

const isEdit = route.params.id ? true : false;
</script>