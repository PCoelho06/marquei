<template>
    <CoelhoModal title="Finalizar assinatura" v-model="showModal">
        <div class="mb-6" v-if="selectedPlan">
            <p class="text-sm text-gray-600 mb-4">
                Você selecionou o plano <strong>{{ selectedPlan.name }}</strong>
                com faturamento <strong>{{ isYearly ? 'anual' : 'mensal' }}</strong>.
            </p>
            <p class="text-lg font-bold text-gray-900 mb-4">
                {{ formatters.formatPrice(selectedPlan.price) }} /
                {{ isYearly ? 'ano' : 'mês' }}
            </p>
        </div>

        <div class="mb-6">
            <div v-show="showPaymentElement" id="payment-element" class="border border-gray-300 p-3 rounded-md">
            </div>
            <div v-show="!showPaymentElement" class="flex justify-center items-center gap-4 text-gray-600">
                <CoelhoSpinner type="dots" size="lg" aria-label="Carregando" />
            </div>
            <div id="payment-errors" role="alert" v-if="paymentError" class="mt-2 text-red-600 text-sm">{{
                paymentError }}</div>
        </div>
        <template #footer>
            <CoelhoButton variant="secondary" @click="showModal = false">
                Cancelar
            </CoelhoButton>
            <CoelhoButton @click="processPayment" v-show="showPaymentElement"
                class="w-full bg-blue-600 hover:bg-blue-700 text-white font-medium py-2 px-4 rounded-md transition duration-150 ease-in-out"
                :disabled="paymentProcessing" :loading="paymentProcessing">
                {{ paymentProcessing ? 'Processando' : 'Confirmar assinatura' }}
            </CoelhoButton>
        </template>
    </CoelhoModal>
</template>

<script setup lang="ts">
import { ref, onMounted, onUnmounted, watch, computed } from 'vue'
import { useRouter } from 'vue-router';
import { loadStripe } from '@stripe/stripe-js'

import { useSubscriptionsStore } from '@/stores/subscriptions';

import { formatters } from '@/utils';

import type { SelectedPlan } from '@/types/subscriptions'

import { CoelhoSpinner, CoelhoModal, CoelhoButton } from '@/components'
import { storeToRefs } from 'pinia';

const { getterClientSecret, getterSubscriptionId, getterStripeSubscriptionId } = storeToRefs(useSubscriptionsStore())

const props = defineProps<{
    selectedPlan: SelectedPlan | null
    isYearly: boolean
}>()

const emit = defineEmits(['cancel'])

const router = useRouter()

const paymentElement = ref<any>(null)
const paymentError = ref('')
const paymentProcessing = ref(false)
const stripe = ref<any>(null)
const elements = ref<any>(null)
const showPaymentElement = ref<boolean>(false)
const showModal = ref<boolean>(!!props.selectedPlan)

watch(() => props.selectedPlan, () => {
    showModal.value = !!props.selectedPlan
})

onMounted(async () => {
    try {
        stripe.value = await loadStripe(import.meta.env.VITE_STRIPE_PUBLIC_KEY)
    } catch (error) {
        console.error('Failed to load Stripe:', error)
    }
})

onUnmounted(() => {
    cleanupStripe()
})

watch(() => getterClientSecret.value, () => {
    if (getterClientSecret.value !== undefined) initPaymentElement()
})

watch(() => showModal.value, (show) => {
    if (!show) {
        closePaymentModal()
    }
})

const initPaymentElement = () => {
    const options = {
        // mode: 'subscription',
        clientSecret: getterClientSecret.value,
    }

    elements.value = stripe.value.elements(options)

    paymentElement.value = elements.value.create('payment', {
        layout: 'tabs'
    })

    paymentElement.value.mount('#payment-element')

    showPaymentElement.value = true

    paymentElement.value.on('change', (event: any) => {
        if (event.error) {
            paymentError.value = event.error.message
        } else {
            paymentError.value = ''
        }
    })
}

const cleanupStripe = () => {
    if (paymentElement.value) {
        paymentElement.value.unmount()
        paymentElement.value = null
    }
}

const closePaymentModal = async () => {
    emit('cancel')

    if (!getterSubscriptionId.value || !getterStripeSubscriptionId.value) {
        return
    }
    await useSubscriptionsStore().cancelSubscription({
        subscriptionId: getterSubscriptionId.value.toString(),
        stripeSubscriptionId: getterStripeSubscriptionId.value
    })

    paymentError.value = ''
    paymentProcessing.value = false

    cleanupStripe()
    showPaymentElement.value = false
}

const processPayment = async () => {
    if (!stripe.value || !paymentElement.value) {
        paymentError.value = 'Erro ao processar pagamento. Por favor, recarregue a página.'
        return
    }

    paymentProcessing.value = true
    paymentError.value = ''

    console.log("🚀 ~ processPayment ~ router.resolve({ name: 'SucceededPayment' }):", window.location.origin + router.resolve({ name: 'SucceededPayment' }).href)
    try {
        const { error } = await stripe.value.confirmPayment({
            elements: elements.value,
            confirmParams: {
                return_url: window.location.origin + router.resolve({ name: 'SucceededPayment' }).href
            }
        });

        if (error) {
            paymentError.value = error.message;
        } else {
            closePaymentModal()
        }
    } catch (error: any) {
        paymentError.value = error.message || 'Ocorreu um erro ao processar o pagamento.'
        console.error('Payment error:', error)
    } finally {
        paymentProcessing.value = false
    }
}
</script>