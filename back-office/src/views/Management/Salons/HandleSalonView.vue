<template>
    <CenteredLayout>
        <div class="container mx-auto w-8/10" v-if="isReady">
            <div class="grid grid-cols-3 items-center my-4">
                <CoelhoLink v-if="getterHasSalons" @click="router.back()" class="flex items-center gap-2">
                    <CoelhoIcon :icon="ArrowLeftIcon" />
                    Voltar
                </CoelhoLink>
                <DefaultLogo />
            </div>
            <CoelhoCard size="full" :title="isEdit ? 'Atualizar o salão' : 'Registar um salão'">
                <SalonForm :isEdit :salonId="Array.isArray(route.params.id) ? route.params.id[0] : route.params.id" />
            </CoelhoCard>
        </div>
    </CenteredLayout>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { storeToRefs } from 'pinia';

import { useSalonsStore } from '@/stores/salons';
import { useAuthStore } from '@/stores/auth';

import { CoelhoLink, CoelhoIcon, CoelhoCard } from '@/components';
import { ArrowLeftIcon } from '@heroicons/vue/24/solid';
import { CenteredLayout } from '@/layouts';
import SalonForm from './lib/SalonForm.vue';
import DefaultLogo from '@/components/Logos/DefaultLogo.vue';

const route = useRoute();
const router = useRouter();

const salonsStore = useSalonsStore();
const authStore = useAuthStore();

const { getterHasSalons, getterUser } = storeToRefs(authStore);

const isEdit = route.params.id ? true : false;

const isReady = ref<boolean>(false);

onMounted(async () => {
    if (!getterUser.value) {
        await authStore.fetchUser();
    }

    if (isEdit) {
        await salonsStore.getSalon({ id: Number(route.params.id) });
    }
    isReady.value = true;
});
</script>