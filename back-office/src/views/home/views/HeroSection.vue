<template>
    <section class="min-h-dvh flex flex-col items-center justify-center bg-whitten mt-14">
        <div class="text-center animate-fadein my-12">
            <CoelhoLogo size="lg" class="mb-8" variant="icon" />
            <CoelhoHeading color="primary" class="font-brand my-8">
                Gerencie seu salão com <span class="italic underline">simplicidade</span>
            </CoelhoHeading>
            <!-- <CoelhoText class="italic font-light">
                Uma solução completa para a gestão dos seus agendamentos, recursos e equipe.
                Otimize seu tempo e aumente seu faturamento.
            </CoelhoText> -->
            <div class="flex justify-center items-center gap-4 mt-12">
                <CoelhoButton size="lg" @click="router.push({ name: 'Registration' })">
                    Começar agora
                </CoelhoButton>
                <CoelhoButton size="lg" :outlined="true" @click="console.log('click')">Agendar uma
                    demonstração
                    grátis
                </CoelhoButton>
            </div>
        </div>
        <!-- <div class="absolute top-20 left-8">
            <img src="@/assets/images/landing/postits.webp" alt="Hero Background" class="w-1/3 h-1/3 object-cover" />
        </div> -->
        <div class="w-full px-8 flex justify-center items-center gap-4">
            <HeroCard size="sm" image="/src/assets/images/landing/hero1.jpg" />
            <HeroCard size="md" image="/src/assets/images/landing/hero2.jpg" />
            <HeroCard size="lg" image="/src/assets/images/landing/hero3.jpg" />
            <HeroCard size="xl" image="/src/assets/images/landing/hero4.jpg" />
            <div
                class="w-96 h-[464px] bg-white rounded-xl border-4 border-primary inset-shadow-sm inset-shadow-primary/70 opacity-0 animate-[fadeIn_1s_ease-in-out_1500ms_forwards]">
            </div>
            <HeroCard size="xl" image="/src/assets/images/landing/hero5.jpg" />
            <HeroCard size="lg" image="/src/assets/images/landing/hero1.jpg" />
            <HeroCard size="md" image="/src/assets/images/landing/hero2.jpg" />
            <HeroCard size="sm" image="/src/assets/images/landing/hero3.jpg" />
        </div>
    </section>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router';
import { CoelhoLogo, CoelhoButton, CoelhoHeading, CoelhoText } from '@/components';
import HeroCard from './HeroCard.vue';

const router = useRouter();
</script>