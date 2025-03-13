<template>
    <div class="min-h-screen flex justify-center items-center bg-stroke py-12 px-4 sm:px-6 lg:px-8">
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
            <div class="mt-12 flex flex-col justify-center items-center gap-8">
                <!-- Feature Toggle -->
                <div class="flex items-center gap-3">
                    <button @click="selectedFeature = 'management'"
                        class="px-4 py-2 rounded-md text-sm font-medium cursor-pointer" :class="[
                            selectedFeature === 'management'
                                ? 'bg-blue-600 text-white'
                                : 'bg-white text-gray-700 hover:bg-gray-50'
                        ]">
                        Gestão
                    </button>
                    <button @click="selectedFeature = 'complete'"
                        class="px-4 py-2 rounded-md text-sm font-medium cursor-pointer" :class="[
                            selectedFeature === 'complete'
                                ? 'bg-blue-600 text-white'
                                : 'bg-white text-gray-700 hover:bg-gray-50'
                        ]">
                        Gestão + Reservas
                    </button>
                </div>

                <!-- Billing Toggle -->
                <div class="flex items-center gap-3">
                    <span :class="[!isYearly ? 'text-gray-900' : 'text-gray-500']">Mensal</span>
                    <button @click="isYearly = !isYearly"
                        class="relative inline-flex h-6 w-11 flex-shrink-0 cursor-pointer rounded-full border-2 border-transparent transition-colors duration-200 ease-in-out focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2"
                        :class="[isYearly ? 'bg-blue-600' : 'bg-gray-900']">
                        <span
                            class="pointer-events-none inline-block h-5 w-5 transform rounded-full bg-white shadow ring-0 transition duration-200 ease-in-out"
                            :class="[isYearly ? 'translate-x-5' : 'translate-x-0']" />
                    </button>
                    <span :class="[isYearly ? 'text-gray-900' : 'text-gray-500']">Anual</span>
                </div>
            </div>

            <!-- Pricing Cards -->
            <div class="mt-12 space-y-4 sm:mt-16 sm:grid sm:grid-cols-3 sm:gap-6 sm:space-y-0">
                <div v-for="plan in plans" :key="plan.name"
                    class="rounded-lg border border-gray-200 shadow-sm bg-white overflow-hidden">
                    <!-- Header avec hauteur fixe -->
                    <div class="p-6 h-48 flex flex-col">
                        <h3 class="text-lg font-medium leading-6 text-gray-900">{{ plan.name }}</h3>
                        <p class="mt-4 text-sm text-gray-500">{{ plan.description }}</p>
                        <div class="flex justify-between items-end mt-auto">
                            <p>
                                <span class="text-4xl font-extrabold text-gray-900">
                                    {{ formatPrice(isYearly ? plan.pricing[selectedFeature].yearly :
                                        plan.pricing[selectedFeature].monthly) }}
                                </span>
                                <span class="text-base font-medium text-gray-500">/{{ isYearly ? 'ano' : 'mês' }}</span>
                            </p>
                            <p v-if="isYearly" class="text-sm text-gray-500">
                                <span
                                    class="relative text-3xl font-extrabold text-gray-500 before:absolute before:bg-primary before:w-full before:h-1 before:top-1/2 before:rotate-10">
                                    {{ formatPrice(plan.pricing[selectedFeature].yearlyNoDiscount) }}
                                </span>
                            </p>
                        </div>
                    </div>

                    <!-- Section bouton -->
                    <div class="px-6 pb-6">
                        <button @click="handleSubscription(plan.name)"
                            class="block w-full cursor-pointer bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out">
                            Escolher
                        </button>
                    </div>

                    <!-- Séparateur -->
                    <div class="border-t border-gray-200"></div>

                    <!-- Section limits avec hauteur fixe -->
                    <div class="px-6 pt-6 h-40">
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
                    </div>

                    <!-- Section fonctionnalités avec scroll si nécessaire -->
                    <div class="px-6 pt-4 pb-8">
                        <h4 class="text-sm font-medium text-gray-900 mb-4">Funcionalidades incluídas:</h4>
                        <ul class="space-y-4 max-h-64 overflow-y-auto">
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

            <!-- Modal de paiement Stripe -->
            <div v-if="showPaymentModal"
                class="fixed inset-0 bg-gray-500 bg-opacity-75 flex items-center justify-center z-50">
                <div class="bg-white rounded-lg p-8 max-w-md w-full">
                    <div class="flex justify-between items-center mb-6">
                        <h3 class="text-lg font-medium text-gray-900">Finalizar assinatura</h3>
                        <button @click="closePaymentModal" class="text-gray-400 hover:text-gray-500">
                            <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                    d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>

                    <div class="mb-6">
                        <p class="text-sm text-gray-600 mb-4">
                            Você selecionou o plano <strong>{{ selectedPlanName }}</strong> ({{ selectedFeature ===
                                'management' ? 'Gestão' : 'Gestão + Reservas' }})
                            com faturamento {{ isYearly ? 'anual' : 'mensal' }}.
                        </p>
                        <p class="text-lg font-bold text-gray-900 mb-4">
                            {{ formatPrice(selectedPlanPrice) }} / {{ isYearly ? 'ano' : 'mês' }}
                        </p>
                    </div>

                    <div class="mb-6">
                        <div id="card-element" class="border border-gray-300 p-3 rounded-md"></div>
                        <div id="card-errors" role="alert" v-if="paymentError" class="mt-2 text-red-600 text-sm">{{
                            paymentError }}</div>
                    </div>

                    <button @click="processPayment"
                        class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out"
                        :disabled="paymentProcessing">
                        {{ paymentProcessing ? 'Processando...' : 'Confirmar assinatura' }}
                    </button>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue'
import { loadStripe } from '@stripe/stripe-js'

import { api } from '@/api'

interface PricingPlan {
    name: string
    description: string
    limits: string[]
    features: string[]
    pricing: {
        management: {
            monthly: number
            yearly: number
            yearlyNoDiscount: number
        }
        complete: {
            monthly: number
            yearly: number
            yearlyNoDiscount: number
        }
    }
    stripeIds?: {
        management: {
            monthly: string
            yearly: string
        }
        complete: {
            monthly: string
            yearly: string
        }
    }
}

const isYearly = ref(false)
const selectedFeature = ref<'management' | 'complete'>('management')
const showPaymentModal = ref(false)
const selectedPlanName = ref('')
const selectedPlanPrice = ref(0)
const selectedPlanStripeId = ref('')
const stripe = ref<any>(null)
const cardElement = ref<any>(null)
const paymentProcessing = ref(false)
const paymentError = ref('')
const subscriptionStatus = ref('')

const plans: PricingPlan[] = [
    {
        name: 'Salão',
        description: 'Perfeito para pequenos salões iniciando sua jornada digital',
        limits: ['1 salão', '3 empregados máximo'],
        features: [
            'Gestão de agendamentos',
            'Gestão de clientes',
            'Relatórios básicos',
            'Suporte por email'
        ],
        pricing: {
            management: {
                monthly: 12.99,
                yearly: 129.99,
                yearlyNoDiscount: 155.88
            },
            complete: {
                monthly: 19.99,
                yearly: 199.99,
                yearlyNoDiscount: 239.88
            }
        },
        stripeIds: {
            management: {
                monthly: 'salon_management_monthly',
                yearly: 'salon_management_yearly'
            },
            complete: {
                monthly: 'salon_complete_monthly',
                yearly: 'salon_complete_yearly'
            }
        }
    },
    {
        name: 'Premium',
        description: 'Para negócios em crescimento com múltiplas localizações',
        limits: ['3 salões', '15 empregados máximo'],
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
                yearly: 359.99,
                yearlyNoDiscount: 431.88
            },
            complete: {
                monthly: 49.99,
                yearly: 499.99,
                yearlyNoDiscount: 599.88
            }
        },
        stripeIds: {
            management: {
                monthly: 'premium_management_monthly',
                yearly: 'premium_management_yearly'
            },
            complete: {
                monthly: 'premium_complete_monthly',
                yearly: 'premium_complete_yearly'
            }
        }
    },
    {
        name: 'Infinity',
        description: 'Solução completa para redes de salões',
        limits: ['5 salões', '50 empregados máximo'],
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
                yearly: 599.99,
                yearlyNoDiscount: 719.88
            },
            complete: {
                monthly: 79.99,
                yearly: 799.99,
                yearlyNoDiscount: 959.88
            }
        },
        stripeIds: {
            management: {
                monthly: 'infinity_management_monthly',
                yearly: 'infinity_management_yearly'
            },
            complete: {
                monthly: 'infinity_complete_monthly',
                yearly: 'infinity_complete_yearly'
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

onMounted(async () => {
    // Charger Stripe au démarrage du composant
    try {
        stripe.value = await loadStripe(import.meta.env.VITE_STRIPE_PUBLIC_KEY)
    } catch (error) {
        console.error('Failed to load Stripe:', error)
    }
})

const handleSubscription = (planName: string) => {
    const plan = plans.find(p => p.name === planName)
    if (!plan) return

    selectedPlanName.value = planName
    selectedPlanPrice.value = isYearly.value ?
        plan.pricing[selectedFeature.value].yearly :
        plan.pricing[selectedFeature.value].monthly

    // Récupérer l'ID Stripe du plan sélectionné
    if (plan.stripeIds) {
        selectedPlanStripeId.value = isYearly.value ?
            plan.stripeIds[selectedFeature.value].yearly :
            plan.stripeIds[selectedFeature.value].monthly
    } else {
        // Fallback si les IDs Stripe ne sont pas définis (pour la compatibilité)
        selectedPlanStripeId.value = planName.toLowerCase() + '-' + selectedFeature.value + (isYearly.value ? '-yearly' : '-monthly')
    }

    // Ouvrir la modale de paiement
    showPaymentModal.value = true

    // Initialiser l'élément de carte Stripe après l'affichage de la modale
    setTimeout(() => {
        initCardElement()
    }, 100)
}

const initCardElement = () => {
    if (!stripe.value) {
        paymentError.value = 'Erro ao carregar Stripe. Por favor, recarregue a página.'
        return
    }

    // Créer les éléments Stripe
    const elements = stripe.value.elements()

    // Créer l'élément de carte et le monter dans le DOM
    cardElement.value = elements.create('payment', {
        style: {
            base: {
                fontSize: '16px',
                color: '#32325d',
                fontFamily: '-apple-system, BlinkMacSystemFont, Segoe UI, Roboto, sans-serif',
                '::placeholder': {
                    color: '#aab7c4'
                }
            },
            invalid: {
                color: '#fa755a',
                iconColor: '#fa755a'
            }
        }
    })

    // Monter l'élément de carte
    cardElement.value.mount('#card-element')

    // Gérer les erreurs de validation
    cardElement.value.on('change', (event: any) => {
        if (event.error) {
            paymentError.value = event.error.message
        } else {
            paymentError.value = ''
        }
    })
}

const closePaymentModal = () => {
    // Nettoyer et fermer la modale
    if (cardElement.value) {
        cardElement.value.unmount()
        cardElement.value = null
    }
    showPaymentModal.value = false
    paymentError.value = ''
    paymentProcessing.value = false
}

const processPayment = async () => {
    if (!stripe.value || !cardElement.value) {
        paymentError.value = 'Erro ao processar pagamento. Por favor, recarregue a página.'
        return
    }

    paymentProcessing.value = true
    paymentError.value = ''

    try {
        // Créer un PaymentMethod avec les informations de carte
        const result = await stripe.value.createPaymentMethod({
            type: 'card',
            card: cardElement.value
        })

        if (result.error) {
            throw new Error(result.error.message)
        }

        const subscription = await api().subscriptions.create({
            planId: selectedPlanStripeId.value,
            paymentMethodId: result.paymentMethod.id
        })

        // const subscription = await response.json()

        if (subscription.error) {
            throw new Error(subscription.error)
        }

        // Gérer le résultat de l'abonnement
        if (subscription.status === 'active') {
            // Succès - rediriger vers une page de confirmation ou mettre à jour l'interface
            subscriptionStatus.value = 'active'
            alert('Assinatura criada com sucesso!')
            closePaymentModal()
            // Éventuellement rediriger vers une page de confirmation
            // window.location.href = '/subscription/success'
        } else if (subscription.status === 'incomplete') {
            // Gérer l'authentification 3D Secure si nécessaire
            const { error, paymentIntent } = await stripe.value.confirmCardPayment(
                subscription.client_secret
            )

            if (error) {
                throw new Error(error.message)
            } else if (paymentIntent.status === 'succeeded') {
                subscriptionStatus.value = 'active'
                alert('Assinatura criada com sucesso!')
                closePaymentModal()
                // window.location.href = '/subscription/success'
            } else {
                subscriptionStatus.value = paymentIntent.status
                alert(`Status da assinatura: ${paymentIntent.status}`)
            }
        } else {
            subscriptionStatus.value = subscription.status
            alert(`Status da assinatura: ${subscription.status}`)
        }

    } catch (error: any) {
        paymentError.value = error.message || 'Ocorreu um erro ao processar o pagamento.'
        console.error('Payment error:', error)
    } finally {
        paymentProcessing.value = false
    }
}

// Nettoyer les éléments Stripe au démontage du composant
const cleanupStripe = () => {
    if (cardElement.value) {
        cardElement.value.unmount()
        cardElement.value = null
    }
}

watch(() => showPaymentModal.value, (newVal) => {
    if (!newVal) {
        cleanupStripe()
    }
})
</script>