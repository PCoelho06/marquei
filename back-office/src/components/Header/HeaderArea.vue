<template>
    <header class="sticky top-0 z-999 flex w-full bg-white drop-shadow-1">
        <div class="flex flex-grow items-center justify-between lg:justify-end py-4 px-4 shadow-2 md:px-6 2xl:px-11">
            <div class="w-64 flex items-center justify-between gap-2 sm:gap-4 lg:hidden">
                <CoelhoButton variant="secondary" size="sm" type="button" class="z-99999 block lg:hidden"
                    @click="toggleSidebar">
                    <CoelhoIcon :icon="Bars3Icon" size="lg" />
                </CoelhoButton>
                <CoelhoLogo size="sm" contrast="dark" :isLink=true :href="router.resolve({ name: 'Home' }).href"
                    class="mx-auto" />
            </div>

            <div class="flex flex-grow items-center justify-between gap-3 2xsm:gap-7">
                <CoelhoCheckbox v-model="isManagementMode" switchOnLabel="Gestão" switchOffLabel="Salão" size="sm"
                    variant="switch" @update:modelValue="switchMode" />
                <HeaderDropdown />
            </div>
        </div>
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
    </header>
</template>

<script setup lang="ts">
import { onMounted, ref, type ComponentPublicInstance } from 'vue';
import { useRouter } from 'vue-router';
import { storeToRefs } from 'pinia';

import { useAuthStore } from '@/stores/auth';
import { useSalonsStore } from '@/stores/salons';
import { useSidebarStore } from '@/stores/sidebar'

import { CoelhoLogo, CoelhoButton, CoelhoIcon, CoelhoModal, CoelhoText } from '@/components';
import HeaderDropdown from '@/views/commons/views/HeaderDropdown.vue';
import { Bars3Icon, CheckBadgeIcon, XMarkIcon } from '@heroicons/vue/24/solid';
import CoelhoCheckbox from '../atoms/CoelhoCheckbox.vue';
import ConfirmPasswordForm from '@/views/authentication/lib/ConfirmPasswordForm.vue';

const router = useRouter()

const authStore = useAuthStore()
const salonStore = useSalonsStore()
const { getterMode } = storeToRefs(authStore)
const { getterSalonList } = storeToRefs(salonStore)
const { toggleSidebar, isSidebarOpen } = useSidebarStore()

const isManagementMode = ref<boolean>(getterMode.value === 'management')
const showModal = ref<boolean>(false)
const showAlert = ref<boolean>(false)
const confirmPasswordFormRef = ref<ComponentPublicInstance & { submitConfirmPasswordForm: () => void }>();

const switchMode = (value: boolean) => {
    if (value) {
        // affichage d'une modale de confirmation avec demande du mot de passe
        showModal.value = true;
        // si le mot de passe est correct -> bascule vers le mode management
        // authStore.actionSelectMode({ mode: 'management' }).then(() => {
        //     router.push({ name: 'ManagementDashboard' });
        // });
    } else {
        if (getterSalonList.value?.length === 1) {
            handleSelectSalon(getterSalonList.value[0].id);
            return;
        }
        showModal.value = true;
    }
}

const handleSelectSalon = (salonId: number) => {
    showModal.value = false
    authStore.actionSelectMode({ mode: 'store', salonId }).then(() => {
        salonStore.getSalon({ id: salonId }).then(() => {
            router.push({ name: 'StoreDashboard' });
        });
    });
};

const cancelModeSwitch = () => {
    showModal.value = false
    isManagementMode.value = !isManagementMode.value
}

const handleConfirmPasswordSubmit = (password: string) => {
    console.log("🚀 ~ handleConfirmPasswordSubmit ~ password:", password)

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

onMounted(() => {
    if (!getterSalonList.value) {
        salonStore.searchSalons({ httpQuery: {} })
    }
})
</script>