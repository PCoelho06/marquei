<template>
    <section class="min-h-dvh flex flex-col items-center justify-center bg-whitten mt-14">
        <div class="text-center animate-fadein my-12">
            <CoelhoLogo size="lg" class="mb-8" />
            <CoelhoHeading color="primary" class="font-brand my-8">
                Gerencie seu salão com <span class="italic underline">simplicidade</span>
            </CoelhoHeading>
            <CoelhoText class="italic font-light">
                Uma solução completa para a gestão dos seus agendamentos, recursos e equipe.
                Otimize seu tempo e aumente seu faturamento.
            </CoelhoText>
            <!-- <p class="text-xl mb-8">
            </p> -->
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
        <div class="w-full px-8 relative flex justify-center items-center gap-4 animate-fadein">
            <hero-card size="sm" class="hidden xl:block">
                <img src="@/assets/images/landing/hero1.jpg" alt="Hero Card 1"
                    class="w-full h-full object-cover rounded-xl" />
            </hero-card>
            <hero-card size="md" class="hidden xl:block">
                <img src="@/assets/images/landing/hero2.jpg" alt="Hero Card 1"
                    class="w-full h-full object-cover rounded-xl" />
            </hero-card>
            <hero-card size="lg" class="hidden lg:block">
                <img src="@/assets/images/landing/hero3.jpg" alt="Hero Card 1"
                    class="w-full h-full object-cover rounded-xl" />
            </hero-card>
            <hero-card size="xl" class="hidden md:block">
                <img src="@/assets/images/landing/hero4.jpg" alt="Hero Card 1"
                    class="w-full h-full object-cover rounded-xl" />
            </hero-card>
            <div
                class="w-96 h-[464px] bg-white rounded-xl border-4 border-primary inset-shadow-sm inset-shadow-primary/70">
            </div>
            <hero-card size="xl" class="hidden md:block">
                <img src="@/assets/images/landing/hero5.jpg" alt="Hero Card 1"
                    class="w-full h-full object-cover rounded-xl" />
            </hero-card>
            <hero-card size="lg" class="hidden lg:block">
                <img src="@/assets/images/landing/hero1.jpg" alt="Hero Card 1"
                    class="w-full h-full object-cover rounded-xl" />
            </hero-card>
            <hero-card size="md" class="hidden xl:block">
                <img src="@/assets/images/landing/hero2.jpg" alt="Hero Card 1"
                    class="w-full h-full object-cover rounded-xl" />
            </hero-card>
            <hero-card size="sm" class="hidden xl:block">
                <img src="@/assets/images/landing/hero3.jpg" alt="Hero Card 1"
                    class="w-full h-full object-cover rounded-xl" />
            </hero-card>
        </div>
    </section>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router';
import { CoelhoLogo, CoelhoButton, CoelhoHeading, CoelhoText } from '@/components';
import HeroCard from './HeroCard.vue';

const router = useRouter();
</script>