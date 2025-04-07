<template>
    <div class="w-full text-center">
        <CoelhoCheckbox v-model="isManagementMode" color="light" switchOnLabel="Gestão" switchOffLabel="Salão" size="sm"
            variant="switch" @update:modelValue="switchMode" />
        <CoelhoModal v-model="showModal" :title="isManagementMode ? 'Palavra-passe necessária' : 'Gerenciar um salão'"
            size="lg" @update:model-value="cancelModeSwitch">
            <div v-if="isManagementMode" class="flex flex-col gap-4">
                <ConfirmPasswordForm @submit="handleConfirmPasswordSubmit" ref="confirmPasswordFormRef" :showAlert />
            </div>
            <div v-else class="flex flex-col gap-4">
                <CoelhoText size="sm" color="secondary">
                    Você está prestes a mudar o modo de operação para gerenciar um salão.
                    Para proseguir, escolha o salão que deseja gerenciar. Caso contrário, clique em "Cancelar".
                </CoelhoText>
                <div class="flex flex-col items-center w-full">
                    <div class="w-full hover:cursor-pointer hover:bg-blue-50 p-2" v-for="salon in getterSalonList"
                        :key="salon.id" @click="() => handleSelectSalon(salon.id)">
                        {{ salon.name }}
                    </div>
                </div>
            </div>
            <template #footer>
                <CoelhoButton variant="secondary" :icon="XMarkIcon" @click="cancelModeSwitch">
                    Cancelar
                </CoelhoButton>
                <CoelhoButton v-if="isManagementMode" :icon="CheckBadgeIcon"
                    @click="confirmPasswordFormRef?.submitConfirmPasswordForm">
                    Confirmar
                </CoelhoButton>
            </template>
        </CoelhoModal>
    </div>
</template>

<script setup lang="ts">
import { ref, type ComponentPublicInstance } from 'vue';
import { useRouter } from 'vue-router';
import { storeToRefs } from 'pinia';

import { useAuthStore } from '@/stores/auth';
import { useSalonsStore } from '@/stores/salons';

import ConfirmPasswordForm from '@/views/authentication/lib/ConfirmPasswordForm.vue';

import { CoelhoCheckbox, CoelhoModal, CoelhoText, CoelhoButton } from '.';
import { CheckBadgeIcon, XMarkIcon } from '@heroicons/vue/24/solid';

const router = useRouter()

const authStore = useAuthStore()
const salonStore = useSalonsStore()
const { getterMode } = storeToRefs(authStore)
const { getterSalonList } = storeToRefs(salonStore)

const isManagementMode = ref<boolean>(getterMode.value === 'management')
const showModal = ref<boolean>(false)
const showAlert = ref<boolean>(false)
const confirmPasswordFormRef = ref<ComponentPublicInstance & { submitConfirmPasswordForm: () => void }>();

const switchMode = async (isManagementMode: boolean) => {
    if (isManagementMode) {
        showModal.value = true;
    } else {
        if (!getterSalonList.value) {
            await salonStore.searchSalons({ httpQuery: {} })
        }
        if (getterSalonList.value?.length === 1) {
            await handleSelectSalon(getterSalonList.value[0].id);
            return;
        }
        showModal.value = true;
    }
}

const handleSelectSalon = async (salonId: number) => {
    showModal.value = false
    await authStore.actionSelectMode({ mode: 'store', salonId }).then(() => {
        salonStore.getSalon({ id: salonId }).then(() => {
            router.push({ name: 'SalonAgenda', params: { salonId } });
        });
    });
};

const cancelModeSwitch = () => {
    showModal.value = false
    isManagementMode.value = !isManagementMode.value
}

const handleConfirmPasswordSubmit = (password: string) => {
    authStore.confirmPassword({ password }).then(() => {
        showModal.value = false;
        authStore.actionSelectMode({ mode: 'management' }).then(() => {
            router.push({ name: 'ManagementDashboard' });
        });
    }).catch((error) => {
        console.error("Erreur lors de la confirmation du mot de passe:", error);
        showAlert.value = true;
    });
}
</script>