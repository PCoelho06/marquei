<template>
    <div class="min-h-screen flex justify-center items-center bg-stroke py-12 px-4 sm:px-6 lg:px-8">
        <div v-if="isReady" class="max-w-7xl mx-auto">
            <CoelhoLink v-if="getterSubscription"
                :to="router.resolve({ name: 'GetSalon', params: { id: route.params.id } }).href"
                class="flex items-center gap-2">
                <CoelhoIcon :icon="ArrowLeftIcon" />
                Voltar
            </CoelhoLink>
            <div class="text-center">
                <h2 class="text-3xl font-extrabold text-gray-900 sm:text-4xl">
                    Planos e Preços
                </h2>
                <p class="mt-4 text-xl text-gray-600">
                    Escolha o plano perfeito para o seu negócio
                </p>
            </div>

            <div class="mt-12 flex flex-col justify-center items-center gap-8">
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

            <div class="mt-12 space-y-4 sm:mt-16 sm:grid sm:grid-cols-2 sm:gap-6 sm:space-y-0">
                <div v-for="plan in plans" :key="plan.name"
                    class="rounded-lg border border-gray-200 shadow-sm bg-white overflow-hidden">
                    <div class="p-6 h-30 flex flex-col">
                        <div class="flex gap-4">
                            <h3 class="text-lg font-medium leading-6 text-gray-900">{{ plan.name }}</h3>
                            <CoelhoBadge size="sm" v-if="plan.isActualPlan">
                                Atual</CoelhoBadge>
                        </div>
                        <p class="mt-4 text-sm text-gray-500">{{ plan.description }}</p>
                    </div>

                    <div class="px-6 pt-4 pb-8">
                        <h4 class="text-sm font-medium text-gray-900 mb-4">Funcionalidades incluídas:</h4>
                        <ul class="space-y-4 max-h-64 overflow-y-auto">
                            <li v-for="feature in plan.includedFeatures" :key="feature" class="flex items-start">
                                <svg class="flex-shrink-0 h-5 w-5 text-green-500" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="ml-3 text-sm text-gray-500">{{ feature }}</span>
                            </li>
                            <li v-for="feature in plan.notIncludedFeatures" :key="feature" class="flex items-start">
                                <svg class="flex-shrink-0 h-5 w-5 text-gray-500" viewBox="0 0 20 20"
                                    fill="currentColor">
                                    <path fill-rule="evenodd"
                                        d="M10 8.586l-4.293-4.293a1 1 0 00-1.414 1.414L8.586 10l-4.293 4.293a1 1 0 001.414 1.414L10 11.414l4.293 4.293a1 1 0 001.414-1.414L11.414 10l4.293-4.293a1 1 0 00-1.414-1.414L10 8.586z"
                                        clip-rule="evenodd" />
                                </svg>
                                <span class="ml-3 text-sm text-gray-500 line-through">{{ feature }}</span>
                            </li>
                        </ul>
                    </div>

                    <div class="flex items-end my-8 mx-4"
                        :class="{ 'justify-end': !isYearly, 'justify-between': isYearly }">
                        <p v-if="isYearly" class="text-sm text-gray-500">
                            <span
                                class="relative text-3xl font-extrabold text-gray-500 before:absolute before:bg-primary before:w-full before:h-1 before:top-1/2 before:rotate-10">
                                {{ formatters.formatPrice(plan.pricing.yearlyNoDiscount) }}
                            </span>
                        </p>
                        <p>
                            <span class="text-5xl font-extrabold text-gray-900">
                                {{ formatters.formatPrice(isYearly ? plan.pricing.yearly :
                                    plan.pricing.monthly) }}
                            </span>
                            <span class="text-base font-medium text-gray-500 ms-2">/{{ isYearly ? 'ano' : 'mês'
                            }}</span>
                        </p>
                    </div>

                    <div class="px-6 pb-6">
                        <CoelhoButton @click="getterSubscription ? switchPlan(plan) : selectPlan(plan)" class="w-full"
                            :disabled="plan.isActualPlan">
                            {{ plan.isActualPlan ? 'Plano atual' : 'Escolher' }}
                        </CoelhoButton>
                    </div>
                </div>
            </div>
            <CoelhoModal v-model="isModalOpen" :title="modal?.title">
                <p>{{ modal?.content }}</p>
                <template #footer>
                    <CoelhoButton @click="isModalOpen = false" variant="secondary">
                        {{ modal?.dismiss }}
                    </CoelhoButton>
                    <CoelhoButton v-if="modal?.validate" @click="modal?.action" variant="primary">
                        {{ modal?.validate }}
                    </CoelhoButton>
                </template>
            </CoelhoModal>

            <PaymentElement v-if="selectedPlan" :selectedPlan :isYearly @cancel="cancelSubscription" />
        </div>
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue'
import { useRoute, useRouter } from 'vue-router'
import { storeToRefs } from 'pinia'

import { formatters } from '@/utils'

import { useSubscriptionsStore } from '@/stores/subscriptions'

import type { PricingPlan, SelectedPlan } from '@/types/subscriptions'

import { CoelhoBadge, CoelhoButton, CoelhoIcon, CoelhoLink, CoelhoModal } from '@coelhoui'
import PaymentElement from '@/components/Payment/PaymentElement.vue'
import { ArrowLeftIcon } from '@heroicons/vue/24/solid'
import type { ModalContent } from '@/types'

const route = useRoute()
const router = useRouter()

const subscriptionsStore = useSubscriptionsStore()
const { getterSubscription, getterSubscriptionId, getterStripeSubscription, getterStripeSubscriptionId, getterClientSecret } = storeToRefs(subscriptionsStore)

const isModalOpen = ref<boolean>(false)
const isYearly = ref<boolean>(false)
const isReady = ref<boolean>(false)
const selectedPlan = ref<SelectedPlan | null>()

const modal = ref<ModalContent>()

const plans = ref<PricingPlan[]>([
    {
        name: 'Gestão',
        description: 'Perfeito para salões iniciando sua jornada digital',
        includedFeatures: [
            'Gestão de agendamentos',
            'Gestão de clientes',
            'Relatórios avançados',
        ],
        notIncludedFeatures: [
            'Reservas 7/7 e 24/24',
            'SMS de lembrete'
        ],
        pricing: {
            monthly: 3999,
            yearly: 39999,
            yearlyNoDiscount: 47988
        },
        priceIds: {
            monthly: 'price_1R1P2vCyaLq5pcktRYrpWYBt',
            yearly: 'price_1R1P5kCyaLq5pcktRv64NUyj'
        }
    },
    {
        name: 'Gestão e reservas',
        description: 'Para salões que querem gerir o negócio e aceitar reservas online',
        includedFeatures: [
            'Gestão de agendamentos',
            'Gestão de clientes',
            'Relatórios avançados',
            'Reservas 7/7 e 24/24',
            'SMS de lembrete'
        ],
        pricing: {
            monthly: 5999,
            yearly: 59999,
            yearlyNoDiscount: 71988
        },
        priceIds: {
            monthly: 'price_1R1PVBCyaLq5pcktGtyHkKaZ',
            yearly: 'price_1R1PVVCyaLq5pcktB3IK7dUo'
        }
    },
])

const selectPlan = async (plan: PricingPlan) => {
    if (!plan) return

    selectedPlan.value = {
        name: plan.name,
        price_id: isYearly.value ? plan.priceIds.yearly : plan.priceIds.monthly,
        price: isYearly.value ? plan.pricing.yearly : plan.pricing.monthly,
        interval: isYearly.value ? 'yearly' : 'monthly'
    }

    await subscriptionsStore.createSubscription({
        priceId: isYearly.value ? plan.priceIds.yearly : plan.priceIds.monthly,
        salonId: Array.isArray(route.params.id) ? route.params.id[0] : route.params.id
    })
}

const switchPlan = async (plan: PricingPlan) => {
    if (!plan) return

    modal.value = {
        title: 'Alterar plano',
        content: 'Tem certeza que deseja alterar o seu plano?',
        dismiss: 'Cancelar',
        validate: 'Alterar',
        action: () => switchSubscription(plan)
    }

    isModalOpen.value = true
}

const switchSubscription = async (plan: PricingPlan) => {
    if (!plan) return

    await subscriptionsStore.switchSubscription({
        priceId: isYearly.value ? plan.priceIds.yearly : plan.priceIds.monthly,
        stripeSubscriptionId: getterStripeSubscription.value?.id,
        stripeItemId: getterStripeSubscription.value.items.data[0].id
    })

    defineActualPlan()

    modal.value = {
        title: 'Plano alterado',
        content: 'O seu plano foi alterado com sucesso!',
        dismiss: 'Fechar',
        validate: 'Tornar ao salão',
        action: () => router.push({ name: 'GetSalon', params: { id: route.params.id } })
    }
}

const cancelSubscription = async () => {
    selectedPlan.value = null

    console.log("🚀 ~ cancelSubscription ~ getterClientSecret.value:", getterClientSecret.value)
}

const defineActualPlan = () => {
    for (const plan in plans.value) {
        if (Object.values(plans.value[plan].priceIds).find((planId) => planId === getterStripeSubscription.value.plan.id)) {
            plans.value[plan].isActualPlan = true
        } else {
            plans.value[plan].isActualPlan = false
        }
    }
    console.log("🚀 ~ defineActualPlan ~ plans:", plans.value)
}

onMounted(async () => {
    await subscriptionsStore.getSubscription({ id: Array.isArray(route.params.id) ? Number(route.params.id[0]) : Number(route.params.id) })

    if (getterSubscription.value) defineActualPlan()
    isReady.value = true
})
</script>