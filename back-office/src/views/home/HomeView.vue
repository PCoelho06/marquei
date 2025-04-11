<template>
    <div class="min-h-screen bg-gradient-to-b from-whitten to-white">
        <HomeHeader :isBackgroundDark :isTop />
        <main>
            <HeroSection />
            <HomeSection>
                <template #content>
                    <CoelhoHeading :level="2" size="md" color="primary" class="text-center">Gestão de Agendamentos
                    </CoelhoHeading>
                    <CoelhoText :is-paragraph="true" class="m-4">
                        Està farto de perder tempo com agendamentos? A Marquei é a solução que você precisa.
                    </CoelhoText>
                    <!-- <CoelhoText :is-paragraph="true" class="m-4">
                Com Marquei, você pode agendar, reagendar e cancelar compromissos com apenas alguns cliques. Não perca
                mais tempo com papelada ou telefonemas.
            </CoelhoText> -->
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
                </template>
                <template #illustration>
                    <RemindersIllustration />
                </template>
            </HomeSection>
            <HomeSection :isEven="false">
                <template #content>
                    <CoelhoHeading :level="2" size="md" color="primary" class="text-center">Gestão de Recursos
                    </CoelhoHeading>
                    <CoelhoText :is-paragraph="true" class="m-4">
                        A Marquei é a solução ideal para gerenciar os recursos do seu salão de beleza.
                    </CoelhoText>
                    <CoelhoText :is-paragraph="true" class="m-4">
                        <ul class="list-disc pl-4">
                            <li>Controle total sobre seus salões e serviços</li>
                            <li>Otimização da agenda dos seus funcionários e maquinas</li>
                            <li>Relatórios detalhados para otimizar seu estoque</li>
                        </ul>
                    </CoelhoText>
                    <div class="flex justify-center">
                        <CoelhoButton size="lg" @click="router.push({ name: 'Registration' })">
                            Começar agora
                        </CoelhoButton>
                    </div>
                </template>
                <template #illustration>
                    <ManageIllustration />
                </template>
            </HomeSection>
            <HomeSection>
                <template #content>
                    <CoelhoHeading :level="2" size="md" color="primary" class="text-center">Gestão de Funcionários
                    </CoelhoHeading>
                    <CoelhoText :is-paragraph="true" class="m-4">
                        A Marquei é a solução ideal para otimizar o trabalho da sua equipe.
                    </CoelhoText>
                    <CoelhoText :is-paragraph="true" class="m-4">
                        <ul class="list-disc pl-4">
                            <li>Gestão automatica dos agendamentos conforme os horarios de presença e as férias</li>
                            <li>Otimização da agenda dos seus funcionários e maquinas</li>
                            <li>Relatórios detalhados para otimizar o desempenho</li>
                        </ul>
                    </CoelhoText>
                    <div class="flex justify-center">
                        <CoelhoButton size="lg" @click="router.push({ name: 'Registration' })">
                            Começar agora
                        </CoelhoButton>
                    </div>
                </template>
                <template #illustration>
                    <EmployeesIllustration />
                </template>
            </HomeSection>
            <HomeSection :isEven="false">
                <template #content>
                    <CoelhoHeading :level="2" size="md" color="primary" class="text-center">Análise e Estatísticas
                    </CoelhoHeading>
                    <CoelhoText :is-paragraph="true" class="m-4">
                        A Marquei é a solução ideal para otimizar o trabalho da sua equipe.
                    </CoelhoText>
                    <CoelhoText :is-paragraph="true" class="m-4">
                        <ul class="list-disc pl-4">
                            <li>Relatórios detalhados para otimizar o desempenho</li>
                            <li>Painéis detalhados para acompanhar o crescimento do seu negócio e tomar as melhores
                                decisões.</li>
                        </ul>
                    </CoelhoText>
                    <div class="flex justify-center">
                        <CoelhoButton size="lg" @click="router.push({ name: 'Registration' })">
                            Começar agora
                        </CoelhoButton>
                    </div>
                </template>
                <template #illustration>
                    <DashboardIllustration />
                </template>
            </HomeSection>
            <section class="bg-primary py-20">
                <div class="container mx-auto flex flex-col items-center gap-8 px-4 text-center">
                    <CoelhoHeading :level="2" size="lg" color="light">
                        Pronto para transformar seu salão?
                    </CoelhoHeading>
                    <CoelhoText :is-paragraph="true" color="light">
                        Junte-se às centenas de profissionais que confiam no Marquei
                        para a gestão diária do seu estabelecimento.
                    </CoelhoText>
                    <CoelhoButton size="lg" variant="secondary" @click="router.push({ name: 'Registration' })">
                        Começar
                        agora
                    </CoelhoButton>
                </div>
            </section>
        </main>
        <!-- <section class="grid grid-cols-2 h-dvh" ref="heroSection">
            <div class="flex flex-col justify-center items-center bg-linear-to-br px-4 py-20 to-primary from-blue-500"
                ref="heroSection">
                <div class="max-w-4xl mx-auto text-center heroSection">
                    <CoelhoLogo size="lg" contrast="light" class="mb-8" />
                    <h2 class="text-3xl font-bold text-whitten mb-6">
                        Gerencie seu salão de beleza com simplicidade
                    </h2>
                    <p class="text-xl text-stroke mb-8">
                        Uma solução completa para a gestão dos seus agendamentos, recursos e equipe.
                        Otimize seu tempo e aumente seu faturamento.
                    </p>
                    <div class="flex flex-col justify-center items-center gap-4">
                        <CoelhoButton size="lg" variant="secondary" @click="router.push({ name: 'Registration' })">
                            Começar agora
                        </CoelhoButton>
                        <CoelhoButton size="lg" :outlined="true" variant="secondary" @click="requestDemo">Agendar uma
                            demonstração
                            grátis
                        </CoelhoButton>
                    </div>
                </div>
            </div>
            <CoelhoCarousel :autoplay="true" :show-arrows="false" :show-dots="false">
                <template #slide-0>
                    <div class="size-full relative transition-transform duration-500 ease-in-out">
                        <img src="@/assets/images/landing/hero1.jpg" alt=""
                            class="absolute inset-0 w-full h-full object-cover ">
                        <div class="absolute bg-black opacity-40 z-10 size-full"></div>
                    </div>
                </template>
<template #slide-1>
                    <div class="size-full relative transition-transform duration-500 ease-in-out">
                        <img src="@/assets/images/landing/hero2.jpg" alt=""
                            class="absolute inset-0 size-full object-cover ">
                        <div class="absolute bg-black opacity-40 z-10 size-full"></div>
                    </div>
                </template>
<template #slide-2>
                    <div class="size-full relative transition-transform duration-500 ease-in-out">
                        <img src="@/assets/images/landing/hero3.jpg" alt=""
                            class="absolute inset-0 size-full object-cover ">
                        <div class="absolute bg-black opacity-40 z-10 size-full"></div>
                    </div>
                </template>
<template #slide-3>
                    <div class="size-full relative transition-transform duration-500 ease-in-out">
                        <img src="@/assets/images/landing/hero4.jpg" alt=""
                            class="absolute inset-0 size-full object-cover ">
                        <div class="absolute bg-black opacity-40 z-10 size-full"></div>
                    </div>
                </template>
<template #slide-4>
                    <div class="size-full relative transition-transform duration-500 ease-in-out">
                        <img src="@/assets/images/landing/hero5.jpg" alt=""
                            class="absolute inset-0 size-full object-cover ">
                        <div class="absolute bg-black opacity-40 z-10 size-full"></div>
                    </div>
                </template>
</CoelhoCarousel>
</section> -->

        <!-- <section id="testimonials" class="container mx-auto px-4 py-20">
            <h2 class="text-4xl font-bold text-center text-dark mb-12">
                O que dizem nossos clientes
            </h2>
            <CoelhoCarousel :autoplay="true" :show-arrows="true" :show-dots="true" class="w-full py-20">
                <template v-for="(testimonial, index) in testimonials" :key="testimonial.name" #[`slide-${index}`]>
                    <div class="w-8/10 mx-auto bg-white p-6 rounded-xl shadow-lg border">
                        <p class="text-strokedark mb-4">{{ testimonial.content }}</p>
                        <div class="flex items-center">
                            <img :src="testimonial.avatar" :alt="testimonial.name"
                                class="w-12 h-12 rounded-full mr-4" />
                            <div>
                                <div class="font-bold text-dark">{{ testimonial.name }}</div>
                                <div class="text-strokedark text-sm">{{ testimonial.role }}</div>
                            </div>
                        </div>
                    </div>
                </template>
            </CoelhoCarousel>
        </section> -->

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
    </div>

</template>

<script setup lang="ts">
import { ref, useTemplateRef, watchEffect } from 'vue'
import { useRouter } from 'vue-router'
import { useElementBounding, useWindowScroll } from '@vueuse/core'

import { CoelhoButton, CoelhoCarousel, CoelhoText, CoelhoHeading } from '@/components'
import RemindersIllustration from './views/RemindersIllustration.vue';
import HomeHeader from './views/HomeHeader.vue'
import HeroSection from './views/HeroSection.vue'
import HomeSection from './views/HomeSection.vue'
import ManageIllustration from './views/ManageIllustration.vue';
import EmployeesIllustration from './views/EmployeesIllustration.vue';
import DashboardIllustration from './views/DashboardIllustration.vue';

interface Testimonial {
    name: string
    role: string
    content: string
    avatar: string
}

const router = useRouter()
const isBackgroundDark = ref(true)
// const heroSection = useTemplateRef('heroSection')
// const { top: topHeroSection, bottom: bottomHeroSection } = useElementBounding(heroSection)
const { y } = useWindowScroll()
const isTop = ref(y.value <= 0)


watchEffect(() => {
    // if (topHeroSection.value <= 60 && bottomHeroSection.value >= 60) {
    //     isBackgroundDark.value = true
    // } else {
    //     isBackgroundDark.value = false
    // }

    if (y.value > 0) {
        isTop.value = false
    } else {
        isTop.value = true
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