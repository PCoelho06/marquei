<template>
    <CoelhoCard v-if="getterSalon" title="Informações gerais">
        <CoelhoData label="Nome" :content="getterSalon.name" />
        <CoelhoData label="Endereço" :content="getterSalon.address" />
        <CoelhoData label="Cidade" :content="getterSalon.postalCode + ', ' + getterSalon.city" />
        <CoelhoData label="Telefone" :content="formatters.formatPhone(getterSalon.phone)" />
        <template #footer>
            <CoelhoButton :icon="PencilIcon"
                :to="router.resolve({ name: 'EditSalon', params: { id: getterSalon?.id } }).href">
                Editar
            </CoelhoButton>
        </template>
    </CoelhoCard>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router';
import { storeToRefs } from 'pinia';

import { formatters } from '@/utils';

import { useSalonsStore } from '@/stores/salons';

import { CoelhoCard, CoelhoButton, CoelhoData } from '@/components';
import { PencilIcon } from '@heroicons/vue/24/solid';

const router = useRouter();

const salonsStore = useSalonsStore();
const { getterSalon } = storeToRefs(salonsStore);
</script>