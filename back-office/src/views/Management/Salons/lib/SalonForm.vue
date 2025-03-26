<template>
    <form @submit.prevent="handleSalon">
        <div class=" m-8 grid grid-cols-1 gap-8 mt-6 sm:grid-cols-2">
            <CoelhoInput :leftIcon="ChatBubbleLeftEllipsisIcon" type="text" label="Nome do salão"
                :error="validationErrors.name" placeholder="Nome do salão" v-model="salon.name"
                autocomplete="organization" :required=true />
            <CoelhoInput :leftIcon="PhoneIcon" type="text" label="Numero de telefone" :error="validationErrors.phone"
                placeholder="Entre o numero de telephone do salão" v-model="salon.phone" autocomplete="tel-local"
                :required=true @input="handlePhoneChange" />
            <CoelhoInput :leftIcon="MapPinIcon" type="text" label="Adresso postal" :error="validationErrors.address"
                placeholder="Entre o adresso do salão" v-model="salon.address" autocomplete="address-level1"
                :required=true />
            <CoelhoInput :leftIcon="InboxIcon" type="text" label="Codigo Postal" :error="validationErrors.postalCode"
                placeholder="Entre o codigo postal" v-model="salon.postalCode" autocomplete="postal-code" :required=true
                @input="handlePostalCodeChange" />
            <CoelhoInput :leftIcon="BuildingOffice2Icon" type="text" label="Cidade" :error="validationErrors.city"
                placeholder="Entre o nome da cidade" v-model="salon.city" autocomplete="address-level2"
                :required=true />
            <CoelhoInput :leftIcon="FlagIcon" type="text" label="Pais" :error="validationErrors.country"
                placeholder="Entre o nome do pais" v-model="salon.country" autocomplete="country-name" :required=true
                :disabled=true />
        </div>
        <div class="flex justify-end">
            <CoelhoButton type="submit" :icon="BookmarkSquareIcon">
                {{ isEdit ? 'Atualizar o salão' : 'Registar o salão' }}
            </CoelhoButton>
        </div>
    </form>
</template>

<script setup lang="ts">
import { onMounted, ref, watchEffect } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { storeToRefs } from 'pinia';

import { validators, formatters } from '@/utils';
import { useSalonsStore } from '@/stores/salons';

import type { SalonCreatePayload } from '@/types/salons';
import type { SalonGeneralInformationValidation } from '@/types/validators';

import { CoelhoButton, CoelhoInput } from '@/components';
import { BookmarkSquareIcon, BuildingOffice2Icon, ChatBubbleLeftEllipsisIcon, InboxIcon, MapPinIcon, PhoneIcon, FlagIcon } from '@heroicons/vue/24/solid';

const route = useRoute();
const router = useRouter();

const salonsStore = useSalonsStore();
const { getterSalon } = storeToRefs(salonsStore);

const isEdit = route.params.id ? true : false;

const salon = ref<SalonCreatePayload>({
    name: '',
    phone: '',
    address: '',
    postalCode: '',
    city: '',
    country: 'Portugal',
});

const validationErrors = ref<SalonGeneralInformationValidation>({
    name: '',
    phone: '',
    address: '',
    postalCode: '',
    city: '',
    country: '',
});

const handleSalon = async () => {
    validationErrors.value = validators.salon.validateSalonData(salon.value);

    if (Object.values(validationErrors.value).some((error) => error !== '')) {
        return;
    }

    try {
        if (isEdit) {
            await salonsStore.updateSalon({ id: Number(route.params.id), ...salon.value });
            router.push({ name: 'GetSalon', params: { id: route.params.id } });
        } else {
            await salonsStore.createSalon(salon.value);
            router.push({ name: 'HandleForfait', params: { id: getterSalon.value?.id.toString() } });
        }
    } catch (error) {
        console.log(error);
    }
};

const handlePhoneChange = () => {
    validationErrors.value.phone = '';

    salon.value.phone = formatters.formatPhone(salon.value.phone);
};

const handlePostalCodeChange = () => {
    validationErrors.value.postalCode = '';

    salon.value.postalCode = formatters.formatPostalCode(salon.value.postalCode);
};

onMounted(() => {
    if (isEdit) {
        salonsStore.getSalon({ id: Number(route.params.id) });
    }

});

watchEffect(() => {
    if (isEdit && getterSalon.value) {
        salon.value.name = getterSalon.value.name ?? '';
        salon.value.phone = formatters.formatPhone(getterSalon.value.phone ?? '');
        salon.value.address = getterSalon.value.address ?? '';
        salon.value.postalCode = formatters.formatPostalCode(getterSalon.value.postalCode ?? '');
        salon.value.city = getterSalon.value.city ?? '';
        salon.value.country = getterSalon.value.country ?? '';
    }
});
</script>