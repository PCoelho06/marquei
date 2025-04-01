<template>
    <header class="fixed left-1/2 -translate-x-1/2 z-999 px-8 py-4 grid grid-cols-3 transition-all " :class="[
        isBackgroundDark ? 'bg-white' : 'bg-primary',
        isTop ? 'w-full top-0 shadow-none' : 'w-8/10 top-2 rounded-md shadow-md',
    ]">
        <nav class="flex justify-between items-center">
            <div class="flex items-center space-x-4 ">
                <router-link v-for="navItem in navItems" :key="navItem.name" :to="{ name: 'Home', hash: navItem.hash }"
                    :class="[isBackgroundDark ? 'text-primary hover:text-dark' : 'text-white hover:text-stroke']">{{
                        navItem.name }}</router-link>
            </div>
        </nav>
        <h1 class="text-2xl font-bold" :class="[isBackgroundDark ? 'text-white' : 'text-primary']">
            <CoelhoLogo size="sm" :contrast="isBackgroundDark ? 'dark' : 'light'"
                :href="router.resolve({ name: 'Home' }).href" />
        </h1>
        <div class="flex justify-end items-center space-x-4">
            <CoelhoButton v-if="!isBackgroundDark" variant="secondary" @click="router.push({ name: 'Login' })">Entrar
            </CoelhoButton>
            <CoelhoButton v-else @click="router.push({ name: 'Login' })">Entrar</CoelhoButton>
        </div>
    </header>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router';

import { CoelhoButton, CoelhoLogo } from '@/components';

const router = useRouter();

const navItems = [
    { name: 'Funcionalidades', hash: '#features' },
    { name: 'Testemunhos', hash: '#testimonials' },
];

defineProps<{
    isBackgroundDark: boolean
    isTop: boolean
}>();
</script>