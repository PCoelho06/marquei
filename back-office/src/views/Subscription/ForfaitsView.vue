<template>
    <div class="min-h-screen bg-gray-100 py-12 px-4 sm:px-6 lg:px-8">
        <div class="max-w-7xl mx-auto">
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    Planos e Preços
                </h2>
                <p class="mt-4 text-xl text-gray-600">
                    Escolha o plano perfeito para o seu negócio
                </p>
            </div>

            <!-- Toggles -->
            <div class="mt-12 flex justify-center gap-8">
                <!-- Billing Toggle -->
                <div class="flex items-center gap-3">
                    <span :class="[!isYearly ? 'text-gray-900' : 'text-gray-500']">Mensal</span>
                    <button @click="isYearly = !isYearly"
                        class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        :class="[isYearly ? 'bg-blue-600' : 'bg-gray-200']">
                        <span
                            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                            :class="[isYearly ? 'translate-x-5' : 'translate-x-0']" />
                    </button>
                    <span :class="[isYearly ? 'text-gray-900' : 'text-gray-500']">Anual</span>
                </div>

                <!-- Feature Toggle -->
                <div class="flex items-center gap-3">
                    <button @click="selectedFeature = 'management'" class="px-4 py-2 rounded-md text-sm font-medium"
                        :class="[
                            selectedFeature === 'management'
                                ? 'bg-blue-600 text-white'
                                : 'bg-white text-gray-700 hover:bg-gray-50'
                        ]">
                        Gestão
                    </button>
                    <button @click="selectedFeature = 'complete'" class="px-4 py-2 rounded-md text-sm font-medium"
                        :class="[
                            selectedFeature === 'complete'
                                ? 'bg-blue-600 text-white'
                                : 'bg-white text-gray-700 hover:bg-gray-50'
                        ]">
                        Gestão + Reservas
                    </button>
                </div>
            </div>

            <!-- Pricing Cards -->
            <div class="mt-12 space-y-4 sm:mt-16 sm:grid sm:grid-cols-3 sm:gap-6 sm:space-y-0">
                <div v-for="plan in plans" :key="plan.name"
                    class="divide-y divide-gray-200 rounded-lg border border-gray-200 shadow-sm bg-white">
                    <div class="p-6">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">{{ plan.name }}</h3>
                        <p class="mt-4 text-sm text-gray-500">{{ plan.description }}</p>
                        <p class="mt-8">
                            <span class="text-4xl font-extrabold text-gray-900">
                                {{ formatPrice(isYearly ? plan.pricing[selectedFeature].yearly :
                                    plan.pricing[selectedFeature].monthly) }}
                            </span>
                            <span class="text-base font-medium text-gray-500">/{{ isYearly ? 'ano' : 'mês' }}</span>
                        </p>
                        <button
                            class="mt-8 block w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                            Começar agora
                        </button>
                    </div>
                    <div class="px-6 pt-6 pb-8">
                        <h4 class="text-sm font-medium text-gray-900 mb-4">Limites:</h4>
                        <ul class="space-y-4">
                            <li v-for="limit in plan.limits" :key="limit" class="flex items-start">
                                <svg class="flex-shrink-0 h-5 w-5 text-green-500" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="ml-3 text-sm text-gray-500">{{ limit }}</span>
                            </li>
                        </ul>

                        <h4 class="text-sm font-medium text-gray-900 mt-6 mb-4">Funcionalidades incluídas:</h4>
                        <ul class="space-y-4">
                            <li v-for="feature in plan.features" :key="feature" class="flex items-start">
                                <svg class="flex-shrink-0 h-5 w-5 text-green-500" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="ml-3 text-sm text-gray-500">{{ feature }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue'

interface PricingPlan {
    name: string
    description: string
    limits: string[]
    features: string[]
    pricing: {
        management: {
            monthly: number
            yearly: number
        }
        complete: {
            monthly: number
            yearly: number
        }
    }
}

const isYearly = ref(false)
const selectedFeature = ref<'management' | 'complete'>('management')

const plans: PricingPlan[] = [
    {
        name: 'Pequeno Salão',
        description: 'Perfeito para pequenos salões iniciando sua jornada digital',
        limits: ['1 salão', '3 funcionários máximo'],
        features: [
            'Gestão de agendamentos',
            'Gestão de clientes',
            'Relatórios básicos',
            'Suporte por email'
        ],
        pricing: {
            management: {
                monthly: 12.99,
                yearly: 129.99
            },
            complete: {
                monthly: 19.99,
                yearly: 199.99
            }
        }
    },
    {
        name: 'Premium',
        description: 'Para negócios em crescimento com múltiplas localizações',
        limits: ['3 salões', '15 funcionários máximo'],
        features: [
            'Todas as funcionalidades do plano Pequeno',
            'Gestão multi-salão',
            'Relatórios avançados',
            'Suporte prioritário',
            'Personalização avançada'
        ],
        pricing: {
            management: {
                monthly: 35.99,
                yearly: 359.99
            },
            complete: {
                monthly: 49.99,
                yearly: 499.99
            }
        }
    },
    {
        name: 'Infinity',
        description: 'Solução completa para redes de salões',
        limits: ['5 salões', '50 funcionários máximo'],
        features: [
            'Todas as funcionalidades do plano Premium',
            'API dedicada',
            'Gestor de conta dedicado',
            'Suporte 24/7',
            'Personalização total'
        ],
        pricing: {
            management: {
                monthly: 59.99,
                yearly: 599.99
            },
            complete: {
                monthly: 79.99,
                yearly: 799.99
            }
        }
    }
]

const formatPrice = (price: number) => {
    return new Intl.NumberFormat('pt-PT', {
        style: 'currency',
        currency: 'EUR'
    }).format(price)
}
</script>