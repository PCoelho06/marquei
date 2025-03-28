<template>
    <CenteredLayout>
        <DoubleCardLayout class="w-8/10 m-auto" :responsiveHideLeft=true>
            <template #leftCard>
                <div class="py-17.5 px-26 text-center">
                    <CoelhoLogo size="md" :titleLevel="1" contrast="dark"
                        :href="router.resolve({ name: 'Home' }).href" />

                    <p class="font-medium 2xl:px-20">
                        Não espere mais para aceder ao seu salão e aproveitar nossos serviços.
                    </p>

                    <img src="@/assets/images/illustrations/authentication.svg" alt="illustration" />
                </div>
            </template>
            <template #rightCard>
                <div class="w-full p-4 sm:p-12.5 xl:p-17.5">
                    <span class="mb-1.5 block font-medium">Comece agora</span>
                    <h2 class="mb-9 text-2xl font-bold text-black sm:text-title-xl2">
                        Aceder ao meu salão na Marquei
                    </h2>
                    <LoginForm />
                </div>
            </template>
        </DoubleCardLayout>
    </CenteredLayout>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router';

import LoginForm from './lib/LoginForm.vue'

import { CenteredLayout, DoubleCardLayout } from '@/layouts'
import { CoelhoLogo } from '@/components';

const router = useRouter();
</script>