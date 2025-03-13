<template>
    <CenteredLayout>
        <div class="flex flex-col w-8/10 h-9/10 justify-center items-center">
            <DefaultLogo />
            <div class="w-1/2 lg:w-2/5">
                <img src="@/assets/images/illustrations/success.svg" alt="illustration" />
            </div>
            <h2 class="text-3xl font-bold text-gray-900 mt-8">Assinatura realizada com sucesso!</h2>
            <p class="text-lg text-gray-600 mt-4 text-center">Agora você pode gerenciar seu salão com a Marquei.</p>
            <button @click="() => router.push({ name: 'ManagementDashboard' })"
                class="bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md mt-8 transition duration-150 ease-in-out">
                Começar a gerenciar
            </button>
        </div>
    </CenteredLayout>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router';

import DefaultLogo from '@/components/Logos/DefaultLogo.vue';
import CenteredLayout from '@/layouts/CenteredLayout.vue';

const router = useRouter();
</script>
