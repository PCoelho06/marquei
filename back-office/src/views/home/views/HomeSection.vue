<template>
    <div class="grid grid-cols-2 my-8 p-8" :class="{ 'invisible': !targetIsVisible, 'animate-fadein': targetIsVisible }"
        ref="target">
        <div class="my-auto">
            <CoelhoHeading :level="2" size="md" color="primary" class="text-center">Gestão de Agendamentos
            </CoelhoHeading>
            <CoelhoText :is-paragraph="true" class="m-4">
                Està farto de perder tempo com agendamentos? A Marquei é a solução que você precisa.
            </CoelhoText>
            <CoelhoText :is-paragraph="true" class="m-4">
                Com Marquei, você pode agendar, reagendar e cancelar compromissos com apenas alguns cliques. Não perca
                mais tempo com papelada ou telefonemas.
            </CoelhoText>
            <CoelhoText :is-paragraph="true" class="m-4">
                <ul class="list-disc pl-4">
                    <li>Agendamentos fáceis e rápidos</li>
                    <li>Notificações automáticas para você e seus clientes</li>
                    <li>Calendário incluido para poder seguir as marcações do dia</li>
                </ul>
            </CoelhoText>
            <CoelhoText :is-paragraph="true" class="m-4">
                Com a Marquei, você pode se concentrar no que realmente importa: seus clientes.
            </CoelhoText>
            <div class="flex justify-center">
                <CoelhoButton size="lg" @click="router.push({ name: 'Registration' })">
                    Começar agora
                </CoelhoButton>
            </div>
        </div>
        <div class="size-full">
            <RemindersIllustration />
        </div>
    </div>
</template>

<script setup lang="ts">
import { useTemplateRef } from 'vue';
import { useRouter } from 'vue-router';
import { useElementVisibility } from '@vueuse/core';

import { CoelhoButton, CoelhoHeading, CoelhoText } from '@/components';
import RemindersIllustration from './RemindersIllustration.vue';

const router = useRouter();

const target = useTemplateRef('target');

const targetIsVisible = useElementVisibility(target);
</script>

<style scoped>
#Device_1 {
    animation: wiggle 1s ease-in-out infinite;
}

@keyframes wiggle {
    0% {
        transform: rotate(0deg);
    }

    50% {
        transform: rotate(5deg);
    }

    100% {
        transform: rotate(0deg);
    }
}
</style>