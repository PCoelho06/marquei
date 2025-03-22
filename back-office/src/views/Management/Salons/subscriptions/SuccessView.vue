<template>
    <CenteredLayout>
        <div class="flex flex-col w-8/10 h-9/10 justify-center items-center">
            <DefaultLogo />
            <div class="w-1/2 lg:w-2/5">
                <img src="@/assets/images/illustrations/success.svg" alt="illustration" />
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mt-8">Assinatura realizada com sucesso!</h2>
            <p class="text-lg text-gray-600 mt-4 text-center">Agora você pode gerenciar seu salão com a Marquei.</p>
            <CoelhoButton :icon="ArrowRightCircleIcon" :to="router.resolve({ name: 'ManagementDashboard' }).href"
                size="lg">
                Começar a gerenciar
            </CoelhoButton>
        </div>
    </CenteredLayout>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router';

import DefaultLogo from '@/components/Logos/DefaultLogo.vue';
import CenteredLayout from '@/layouts/CenteredLayout.vue';
import { CoelhoButton } from '@/components';
import { ArrowRightCircleIcon } from '@heroicons/vue/24/solid';

const router = useRouter();
</script>
