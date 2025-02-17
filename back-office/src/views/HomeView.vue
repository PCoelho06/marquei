<template>
  <div class="min-h-screen bg-gradient-to-b from-whitten to-white">
    <!-- Header -->
    <header class="container mx-auto px-4 py-6">
      <nav class="flex justify-between items-center">
        <div class="text-2xl font-bold text-primary">💈 Marquei</div>
        <div class="flex items-center space-x-4 ">
          <a href="#features" class="text-dark hover:text-primary">Funcionalidades</a>
          <a href="#testimonials" class="text-dark hover:text-primary">Testemunhos</a>
          <OutlineButton value="Entrar" size="sm" @click="router.push('/signin')" />
        </div>
      </nav>
    </header>

    <!-- Hero Section -->
    <section class="container mx-auto px-4 py-20">
      <div class="max-w-4xl mx-auto text-center">
        <h1 class="text-5xl font-bold text-dark mb-6">
          Gerencie seu salão de beleza com simplicidade
        </h1>
        <p class="text-xl text-strokedark mb-8">
          Uma solução completa para a gestão dos seus agendamentos, recursos e equipe.
          Otimize seu tempo e aumente seu faturamento.
        </p>
        <div class="flex justify-center space-x-4">
          <DefaultButton value="Começar agora" @click="router.push('/signup')" />
          <OutlineButton value="Agendar uma demonstração gratis" @click="requestDemo" />
        </div>
      </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="container mx-auto px-4 py-20">
      <h2 class="text-4xl font-bold text-center text-dark mb-12">
        Tudo o que você precisa
      </h2>
      <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-8">
        <div v-for="feature in features" :key="feature.title"
          class="bg-white p-6 rounded-xl shadow-lg hover:shadow-xl transition">
          <div class="text-4xl mb-4">{{ feature.icon }}</div>
          <h3 class="text-xl font-bold text-dark mb-2">{{ feature.title }}</h3>
          <p class="text-strokedark">{{ feature.description }}</p>
        </div>
      </div>
    </section>

    <!-- Testimonials Section -->
    <section id="testimonials" class="container mx-auto px-4 py-20">
      <h2 class="text-4xl font-bold text-center text-dark mb-12">
        O que dizem nossos clientes
      </h2>
      <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-4xl mx-auto">
        <div v-for="testimonial in testimonials" :key="testimonial.name" class="bg-white p-6 rounded-xl shadow-lg">
          <p class="text-strokedark mb-4">{{ testimonial.content }}</p>
          <div class="flex items-center">
            <img :src="testimonial.avatar" :alt="testimonial.name" class="w-12 h-12 rounded-full mr-4" />
            <div>
              <div class="font-bold text-dark">{{ testimonial.name }}</div>
              <div class="text-strokedark text-sm">{{ testimonial.role }}</div>
            </div>
          </div>
        </div>
      </div>
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
          <DefaultButton value="Experimente Grátis" type="secondary" @click="requestDemo" />
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
  </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'

import OutlineButton from '@/components/Buttons/OutlineButton.vue'
import DefaultButton from '@/components/Buttons/DefaultButton.vue'

interface Feature {
  title: string
  description: string
  icon: string
}

interface Testimonial {
  name: string
  role: string
  content: string
  avatar: string
}

const router = useRouter()

const features: Feature[] = [
  {
    title: "Gestão de Agendamentos",
    description: "Agende e gerencie facilmente todas as suas marcações. Sistema de lembretes automáticos e confirmação online.",
    icon: "📅"
  },
  {
    title: "Gestão de Recursos",
    description: "Otimize a utilização dos seus equipamentos e produtos. Controle de estoque em tempo real.",
    icon: "📦"
  },
  {
    title: "Gestão de Funcionários",
    description: "Horários, desempenho e gestão de férias. Tudo para otimizar o trabalho da sua equipe.",
    icon: "👥"
  },
  {
    title: "Análise e Estatísticas",
    description: "Painéis detalhados para acompanhar o crescimento do seu negócio e tomar as melhores decisões.",
    icon: "📊"
  }
]

const testimonials: Testimonial[] = [
  {
    name: "Sofia Santos",
    role: "Proprietária - Salão Elegância",
    content: "Esta aplicação revolucionou a gestão do meu salão. Poupo um tempo precioso todos os dias!",
    avatar: "/api/placeholder/100/100"
  },
  {
    name: "Marco Silva",
    role: "Gerente - Beauty Style Lisboa",
    content: "Uma ferramenta indispensável para qualquer salão moderno. O apoio ao cliente é excepcional.",
    avatar: "/api/placeholder/100/100"
  }
]

const showDemoModal = ref(false)
const email = ref('')

const requestDemo = () => {
  showDemoModal.value = true
}
</script>