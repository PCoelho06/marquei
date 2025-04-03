<template>
    <header class="sticky top-0 z-999 flex w-full bg-white drop-shadow-1">
        <div class="flex flex-grow items-center justify-between lg:justify-end py-4 px-4 shadow-2 md:px-6 2xl:px-11">
            <div class="w-64 flex items-center justify-between gap-2 sm:gap-4 lg:hidden">
                <CoelhoButton variant="secondary" size="sm" type="button" class="z-99999 block lg:hidden"
                    @click="toggleSidebar">
                    <CoelhoIcon :icon="Bars3Icon" size="lg" />
                </CoelhoButton>
                <CoelhoLogo size="sm" contrast="dark" :isLink=true :href="router.resolve({ name: 'Home' }).href"
                    class="mx-auto" />
            </div>

            <div class="flex flex-grow items-center"
                :class="{ 'justify-end': !goBackLink, 'justify-between': goBackLink }">
                <CoelhoLink v-if="goBackLink" @click="router.back()" class="flex items-center gap-2">
                    <CoelhoIcon :icon="ArrowLeftIcon" />
                    Voltar
                </CoelhoLink>
                <HeaderDropdown />
            </div>
        </div>
    </header>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router';

import { useSidebarStore } from '@/stores/sidebar'

import { CoelhoLogo, CoelhoButton, CoelhoIcon, CoelhoLink } from '@/components';
import HeaderDropdown from '@/views/commons/views/HeaderDropdown.vue';
import { Bars3Icon, ArrowLeftIcon } from '@heroicons/vue/24/solid';

withDefaults(defineProps<{
    goBackLink?: boolean
}>(), {
    goBackLink: false
})

const router = useRouter()

const { toggleSidebar } = useSidebarStore()
</script>