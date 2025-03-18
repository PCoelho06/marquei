<template>
  <DefaultLayout :isBackgroundDark>

    <section class="grid grid-cols-2 h-dvh" ref="heroSection">
      <div class="flex flex-col justify-center items-center bg-linear-to-br px-4 py-20 to-primary from-blue-500"
        ref="heroSection">
        <div class="max-w-4xl mx-auto text-center heroSection">
          <DefaultLogo size="lg" contrast="light" :isTitle=true class="mb-8" />
          <h2 class="text-3xl font-bold text-whitten mb-6">
            Gerencie seu salão de beleza com simplicidade
          </h2>
          <p class="text-xl text-stroke mb-8">
            Uma solução completa para a gestão dos seus agendamentos, recursos e equipe.
            Otimize seu tempo e aumente seu faturamento.
          </p>
          <div class="flex flex-col justify-center items-center gap-4">
            <CoelhoButton size="lg" variant="secondary" @click="router.push({ name: 'Registration' })">Começar agora
            </CoelhoButton>
            <CoelhoButton size="lg" :outlined="true" variant="secondary" @click="requestDemo">Agendar uma demonstração
              grátis
            </CoelhoButton>
          </div>
        </div>
      </div>
      <CoelhoCarousel :autoplay="true" :show-arrows="false" :show-dots="false">
        <template #slide-0>
          <div class="size-full relative transition-transform duration-500 ease-in-out">
            <img src="@/assets/images/landing/hero1.jpg" alt="" class="absolute inset-0 w-full h-full object-cover ">
            <div class="absolute bg-black opacity-40 z-10 size-full"></div>
          </div>
        </template>
        <template #slide-1>
          <div class="size-full relative transition-transform duration-500 ease-in-out">
            <img src="@/assets/images/landing/hero2.jpg" alt="" class="absolute inset-0 size-full object-cover ">
            <div class="absolute bg-black opacity-40 z-10 size-full"></div>
          </div>
        </template>
        <template #slide-2>
          <div class="size-full relative transition-transform duration-500 ease-in-out">
            <img src="@/assets/images/landing/hero3.jpg" alt="" class="absolute inset-0 size-full object-cover ">
            <div class="absolute bg-black opacity-40 z-10 size-full"></div>
          </div>
        </template>
        <template #slide-3>
          <div class="size-full relative transition-transform duration-500 ease-in-out">
            <img src="@/assets/images/landing/hero4.jpg" alt="" class="absolute inset-0 size-full object-cover ">
            <div class="absolute bg-black opacity-40 z-10 size-full"></div>
          </div>
        </template>
        <template #slide-4>
          <div class="size-full relative transition-transform duration-500 ease-in-out">
            <img src="@/assets/images/landing/hero5.jpg" alt="" class="absolute inset-0 size-full object-cover ">
            <div class="absolute bg-black opacity-40 z-10 size-full"></div>
          </div>
        </template>
      </CoelhoCarousel>
    </section>

    <AnimatedSection class="bg-primary/40 h-[600px] flex justify-center items-center" animationType="scale">
      <div class="p-4 flex items-center justify-center">
        <div>
          <h2 class="text-3xl font-bold text-white mb-6">Gestão de Agendamentos</h2>
          <p class="text-whitten">
            Agende e gerencie facilmente todas as suas marcações. Sistema de lembretes automáticos e
            confirmação online
          </p>
        </div>
        <div class="size-full">
          <img src="@/assets/images/illustrations/reminders.svg" alt="Gestão de Agendamentos" />
        </div>
      </div>
    </AnimatedSection>

    <AnimatedSection class="bg-primary/40 h-[600px] flex justify-center items-center" animationType="scale">
      <div class="p-4 flex items-center justify-center">
        <div class="size-full">
          <img src="@/assets/images/illustrations/manage.svg" alt="Gestão de Recursos" />
        </div>
        <div>
          <h2 class="text-3xl font-bold text-white mb-6">Gestão de Recursos</h2>
          <p class="text-whitten">
            Otimize a utilização dos seus equipamentos e produtos. Controle o seu stock em tempo real.
          </p>
        </div>
      </div>
    </AnimatedSection>

    <AnimatedSection class="bg-primary/40 h-[600px] flex justify-center items-center" animationType="scale">
      <div class="p-4 flex items-center justify-center">
        <div>
          <h2 class="text-3xl font-bold text-white mb-6">Gestão de Funcionários</h2>
          <p class="text-whitten">
            Horários, desempenho e gestão de férias. Tudo para otimizar o trabalho da sua equipe.
          </p>
        </div>
        <div class="size-full">
          <img src="@/assets/images/illustrations/employees.svg" alt="Gestão de Funcionários" />
        </div>
      </div>
    </AnimatedSection>

    <AnimatedSection class="bg-primary/40 h-[600px] flex justify-center items-center" animationType="scale">
      <div class="p-4 flex items-center justify-center">
        <div class="size-full">
          <img src="@/assets/images/illustrations/dashboard.svg" alt="Análise e Estatísticas" />
        </div>
        <div>
          <h2 class="text-3xl font-bold text-white mb-6">Análise e Estatísticas</h2>
          <p class="text-whitten">
            Painéis detalhados para acompanhar o crescimento do seu negócio e tomar as melhores decisões.
          </p>
        </div>
      </div>
    </AnimatedSection>

    <AnimatedSection class="bg-primary h-[600px] flex justify-center items-center" animationType="scale"
      ref="callToActionSection">
      <div class="flex flex-col">
        <h2 class="text-3xl font-bold text-white mb-6">Não perca tempo e comece jà a geranciar o seu salão com Marquei
        </h2>
        <div class="p-4 flex items-center justify-center space-x-8">
          <CoelhoButton size="lg" variant="secondary" @click="router.push({ name: 'Registration' })">Começar agora
          </CoelhoButton>
          <CoelhoButton size="lg" :outlined="true" variant="secondary" @click="requestDemo">Agendar uma demonstração
            grátis
          </CoelhoButton>
        </div>
      </div>
    </AnimatedSection>

    <section id="testimonials" class="container mx-auto px-4 py-20">
      <h2 class="text-4xl font-bold text-center text-dark mb-12">
        O que dizem nossos clientes
      </h2>
      <CoelhoCarousel :autoplay="true" :show-arrows="true" :show-dots="true" class="w-full py-20">
        <template v-for="(testimonial, index) in testimonials" :key="testimonial.name" #[`slide-${index}`]>
          <div class="w-8/10 mx-auto bg-white p-6 rounded-xl shadow-lg border">
            <p class="text-strokedark mb-4">{{ testimonial.content }}</p>
            <div class="flex items-center">
              <img :src="testimonial.avatar" :alt="testimonial.name" class="w-12 h-12 rounded-full mr-4" />
              <div>
                <div class="font-bold text-dark">{{ testimonial.name }}</div>
                <div class="text-strokedark text-sm">{{ testimonial.role }}</div>
              </div>
            </div>
          </div>
        </template>
      </CoelhoCarousel>
    </section>

    <!-- CTA Section -->
    <section class="bg-primary py-20">
      <div class="container mx-auto px-4 text-center">
        <h2 class="text-4xl font-bold text-white mb-6">
          Pronto para transformar seu salão?
        </h2>
        <p class="text-whitten mb-8 max-w-2xl mx-auto">
          Junte-se às centenas de profissionais que confiam no Marquei
          para a gestão diária do seu estabelecimento.
        </p>
        <div class="max-w-md mx-auto">
          <CoelhoButton size="lg" variant="secondary" @click="router.push({ name: 'Registration' })">Começar agora
          </CoelhoButton>
        </div>
      </div>
    </section>

    <!-- Modal de demonstração -->
    <div v-if="showDemoModal" class="fixed inset-0 bg-boxdark bg-opacity-50 flex items-center justify-center">
      <div class="bg-white p-8 rounded-xl max-w-md w-full">
        <h3 class="text-2xl font-bold text-dark mb-4">Agendar uma demonstração</h3>
        <p class="text-strokedark mb-6">
          Entraremos em contacto consigo em breve para agendar uma demonstração
          personalizada do Marquei.
        </p>
        <div class="flex justify-end">
          <button @click="showDemoModal = false"
            class="bg-primary text-white px-6 py-2 rounded-full hover:bg-primary/90 transition">
            Fechar
          </button>
        </div>
      </div>
    </div>
  </DefaultLayout>

</template>

<script setup lang="ts">
import { ref, useTemplateRef, watchEffect } from 'vue'
import { useRouter } from 'vue-router'
import { useElementBounding } from '@vueuse/core'

import { CoelhoButton, CoelhoCarousel } from '@/components'
import DefaultLayout from '@/layouts/DefaultLayout.vue'
import DefaultLogo from '@/components/Logos/DefaultLogo.vue'
import AnimatedSection from '@/components/Sections/AnimatedSection.vue'

interface Testimonial {
  name: string
  role: string
  content: string
  avatar: string
}

const router = useRouter()
const isBackgroundDark = ref(true)
const heroSection = useTemplateRef('heroSection')
const callToActionSection = useTemplateRef('callToActionSection')
const { top: topHeroSection, bottom: bottomHeroSection } = useElementBounding(heroSection)
const { top: topCallToActionSection, bottom: bottomCallToActionSection } = useElementBounding(callToActionSection)

watchEffect(() => {
  if (topHeroSection.value <= 60 && bottomHeroSection.value >= 60 || topCallToActionSection.value <= 60 && bottomCallToActionSection.value >= 60) {
    isBackgroundDark.value = true
  } else {
    isBackgroundDark.value = false
  }
})

const testimonials: Testimonial[] = [
  {
    name: "Sofia Santos",
    role: "Proprietária - Salão Elegância",
    content: "Esta aplicação revolucionou a gestão do meu salão. Poupo um tempo precioso todos os dias!",
    avatar: '/images/testimonials/user1.png'
  },
  {
    name: "Marco Silva",
    role: "Gerente - Beauty Style Lisboa",
    content: "Uma ferramenta indispensável para qualquer salão moderno. O apoio ao cliente é excepcional.",
    avatar: "/images/testimonials/user2.png"
  }
]

const showDemoModal = ref(false)

const requestDemo = () => {
  showDemoModal.value = true
}
</script>

<style scoped>
.heroSection {
  animation: heroSection 1s ease-in-out;
}

@keyframes heroSection {
  0% {
    opacity: 0;
    transform: translateY(50%);
  }

  100% {
    opacity: 1;
    transform: translateY(0%);
  }
}
</style>