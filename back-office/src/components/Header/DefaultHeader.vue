<template>
    <header class="fixed top-0 z-999 flex w-full">
        <div class="flex flex-grow items-center justify-between py-4 px-4 md:px-6 2xl:px-11">
            <h1 class="text-2xl font-bold" :class="[isBackgroundDark ? 'text-white' : 'text-primary']">
                <DefaultLogo size="sm" :contrast="isBackgroundDark ? 'light' : 'dark'" :is-link=true
                    :href="router.resolve({ name: 'Home' }).href" />
            </h1>
            <nav class="flex justify-between items-center">
                <div class="flex items-center space-x-4 ">
                    <router-link v-for="navItem in navItems" :key="navItem.name"
                        :to="{ name: 'Home', hash: navItem.hash }"
                        :class="[isBackgroundDark ? 'text-white hover:text-stroke' : 'text-primary hover:text-dark']">{{
                            navItem.name }}</router-link>
                </div>
            </nav>
            <CoelhoButton v-if="isBackgroundDark" :outlined="true" variant="secondary"
                @click="router.push({ name: 'Login' })">Entrar</CoelhoButton>
            <CoelhoButton v-else @click="router.push({ name: 'Login' })">Entrar</CoelhoButton>
        </div>
    </header>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router';

import { CoelhoButton } from '@/components';

import DefaultLogo from '../Logos/DefaultLogo.vue';

const router = useRouter();

const navItems = [
    { name: 'Funcionalidades', hash: '#features' },
    { name: 'Testemunhos', hash: '#testimonials' },
];

defineProps<{
    isBackgroundDark: boolean
}>();
</script>