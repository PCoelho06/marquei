<template>
    <CoelhoCard title="Configurações da subscrição" v-if="isReady">
        <div v-if="getterSubscription" class="flex flex-col py-2 px-4">
            <CoelhoData label="Plano" content="Gestão" />
            <CoelhoData label="Preço" :content="getterStripeSubscription['plan']['amount'] / 100 + '€'"
                :contentClass="getterStripeSubscription['plan']['amount'] === 0 ? 'text-green-500' : ''" />
            <CoelhoData label="Periodicidade" content="Mensal" />
            <CoelhoData label="Proxima faturação"
                :content="formatters.formatDateFromTimestamp(getterStripeSubscription['current_period_end'])" />
        </div>
        <div v-else>
            <div class="flex flex-col m-auto">
                <p class="text-sm text-gray-500">Nenhum plano subscrevido</p>
            </div>
        </div>
        <template v-if="getterSubscription" #footer>
            <CoelhoButton :icon="ArrowPathRoundedSquareIcon"
                :to="router.resolve({ name: 'HandleForfait', params: { id: getterSalon?.id } }).href">
                Mudar o plano</CoelhoButton>
            <CoelhoButton :icon="XMarkIcon" @click="$emit('delete:subscription')" variant="danger">
                Cancelar subscrição
            </CoelhoButton>
        </template>
        <template v-else #footer>
            <CoelhoButton :icon="ShoppingCartIcon"
                :to="router.resolve({ name: 'HandleForfait', params: { id: getterSalon?.id } }).href">
                Subscrever agora
            </CoelhoButton>
        </template>
    </CoelhoCard>
    <CoelhoCard v-else>
        <div class="flex flex-col justify-center items-center gap-4">
            <CoelhoSpinner />
        </div>
    </CoelhoCard>
    <CoelhoModal v-model="showModal" title="Cancelar subscrição">
        <CoelhoText>Tem a certeza que deseja cancelar sua subscrição ? Esta ação é irreversível.</CoelhoText>
        <template #footer>
            <CoelhoButton :icon="XMarkIcon" @click="showModal = false" variant="secondary">
                Não, manter
            </CoelhoButton>
            <CoelhoButton :icon="TrashIcon" @click="cancelSubscription" variant="danger">
                Sim, cancelar
            </CoelhoButton>
        </template>
    </CoelhoModal>
</template>

<script setup lang="ts">
import { ref, onMounted, watch } from 'vue';
import { useRouter } from 'vue-router';
import { storeToRefs } from 'pinia';

import { useSalonsStore } from '@/stores/salons';
import { useSubscriptionsStore } from '@/stores/subscriptions';

import { formatters } from '@/utils';

import type { SubscriptionCancelPayload } from '@/types/subscriptions'

import { CoelhoCard, CoelhoButton, CoelhoData, CoelhoModal, CoelhoText, CoelhoSpinner } from '@/components';
import { ArrowPathRoundedSquareIcon, XMarkIcon, ShoppingCartIcon, TrashIcon } from '@heroicons/vue/24/solid';

const router = useRouter();

const props = defineProps<{
    salonId: number;
}>();

const subscriptionsStore = useSubscriptionsStore();
const salonsStore = useSalonsStore();

const { getterSubscription, getterStripeSubscription } = storeToRefs(subscriptionsStore);
const { getterSalon } = storeToRefs(salonsStore);

const showModal = ref(false);
const isReady = ref(getterSubscription.value !== undefined);

const cancelSubscription = () => {
    const payload: SubscriptionCancelPayload = {
        subscriptionId: String(getterSubscription.value?.id),
        stripeSubscriptionId: getterStripeSubscription.value?.id,
    };
    subscriptionsStore.cancelSubscription(payload).then(() => {
        showModal.value = false;
    });
}

onMounted(async () => {
    await subscriptionsStore.getSubscription({ id: props.salonId })
});

watch(() => getterSubscription.value, async () => {
    isReady.value = true;
});
</script>