<template>
    <div class="h-screen flex flex-col justify-around items-center">
        <h1 class="font-bold text-5xl">💈 Marquei</h1>
        <DoubleCard @clickLeft="() => handleClick('management')" @clickRight="() => handleClick('store')">
            <template #leftCard>
                <img src="@/assets/images/illustrations/management-mode.svg" alt="illustration" />
                <h2 class="text-3xl font-bold">
                    Modo de gestão
                </h2>
            </template>
            <template #rightCard>
                <img src="@/assets/images/illustrations/store-mode.svg" alt="illustration" />
                <h2 class="text-3xl font-bold">
                    Modo de loja
                </h2>
            </template>
        </DoubleCard>
        <DialogModal :showModal title="Selecione o salão que deseja gerenciar">
            <template #default>
                <div class="flex flex-col items-center w-full">
                    <div class="w-full hover:cursor-pointer hover:bg-blue-50 p-2" v-for="salon in getterSalons"
                        :key="salon.id" @click="() => console.log(salon.name)">
                        {{ salon.name }}
                    </div>
                </div>
            </template>
        </DialogModal>
    </div>
</template>

<script setup lang="ts">
import { ref, onMounted } from 'vue';
import { storeToRefs } from 'pinia';
import { useRouter } from 'vue-router';

import { useSalonsStore } from '@/stores/salons';
import { useAuthStore } from '@/stores/auth';

import DoubleCard from '@/components/Cards/DoubleCard.vue';
import DialogModal from '@/components/Modals/DialogModal.vue';

const router = useRouter();

const salonStore = useSalonsStore();
const authStore = useAuthStore();
const { getterSalons } = storeToRefs(salonStore);

const showModal = ref(false);

const handleClick = (mode: 'management' | 'store') => {
    if (mode === 'management') {
        authStore.actionSelectMode({ mode }).then(() => {
            router.push({ name: 'ManagementDashboard' });
        });
    } else {
        if (getterSalons.value?.length === 1) {
            handleSelectSalon(getterSalons.value[0].id);
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
    await salonStore.listSalons();
    console.log("🚀 ~ getterSalons:", getterSalons.value)
});
</script>