<template>
    <CenteredLayout>
        <div class="h-screen w-screen flex flex-col justify-around items-center">
            <DefaultLogo size="lg" :is-title="true" />
            <DoubleCardLayout class="w-8/10 m-auto" :clickLeft="() => handleClick('management')"
                :clickRight="() => handleClick('store')">
                <template #leftCard>
                    <div class="py-8 px-12 xl:py-17.5 xl:px-26 text-center">
                        <CoelhoHeading size="2xl">
                            Gestão
                        </CoelhoHeading>
                        <img src="@/assets/images/illustrations/management-mode.svg" alt="illustration" />
                        <CoelhoText size="sm">
                            Acesse o painel de controle global e gerencie seus serviços, funcionários e clientes.
                        </CoelhoText>
                    </div>
                </template>
                <template #rightCard>
                    <div class="py-8 px-12 xl:py-17.5 xl:px-26 text-center">
                        <CoelhoHeading size="2xl">
                            Salão
                        </CoelhoHeading>
                        <img src="@/assets/images/illustrations/store-mode.svg" alt="illustration" />
                        <CoelhoText size="sm">
                            Acesse o painel de controle do seu salão e gerencie seus agendamentos.
                        </CoelhoText>
                    </div>
                </template>
            </DoubleCardLayout>
        </div>
        <CoelhoModal v-model="showModal" title="Selecione o salão que deseja gerenciar">
            <template #default>
                <div class="flex flex-col items-center w-full">
                    <div class="w-full hover:cursor-pointer hover:bg-blue-50 p-2" v-for="salon in getterSalonList"
                        :key="salon.id" @click="() => handleSelectSalon(salon.id)">
                        {{ salon.name }}
                    </div>
                </div>
            </template>
        </CoelhoModal>
    </CenteredLayout>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { storeToRefs } from 'pinia';
import { useRouter } from 'vue-router';

import { useSalonsStore } from '@/stores/salons';
import { useAuthStore } from '@/stores/auth';

import DefaultLogo from '@/components/Logos/DefaultLogo.vue';
import { DoubleCardLayout, CenteredLayout } from '@/layouts';
import { CoelhoHeading, CoelhoModal, CoelhoText } from '@/components';

const router = useRouter();

const salonStore = useSalonsStore();
const authStore = useAuthStore();
const { getterSalonList } = storeToRefs(salonStore);

const showModal = ref(false);

const handleClick = (mode: 'management' | 'store') => {
    if (mode === 'management') {
        authStore.actionSelectMode({ mode }).then(() => {
            router.push({ name: 'ManagementDashboard' });
        });
    } else {
        if (getterSalonList.value?.length === 1) {
            handleSelectSalon(getterSalonList.value[0].id);
            return;
        }
        showModal.value = true;
    }
};

const handleSelectSalon = (salonId: number) => {
    authStore.actionSelectMode({ mode: 'store', salonId }).then(() => {
        salonStore.getSalon({ id: salonId }).then(() => {
            router.push({ name: 'StoreDashboard' });
        });
    });
};

onMounted(async () => {
    await salonStore.searchSalons({ httpQuery: {} });
    console.log("🚀 ~ getterSalons:", getterSalonList.value)
});
</script>