<template>
    <div class="container mx-auto">
        <div class="flex justify-between items-center mb-4" v-if="isEdit">
            <h1 class="text-2xl font-semibold">Editar o salão : Informações gerais</h1>
        </div>
        <div class="flex justify-between items-center mb-4" v-else>
            <h1 class="text-2xl font-semibold">Registar o salão : Informações gerais</h1>
            <p class="font-bold">1 / 2</p>
        </div>
        <DefaultCard cardTitle="Informações do salão">
            <div class="p-8">
                <form @submit.prevent="handleSalon">
                    <div class="grid grid-cols-1 gap-6 mt-6 sm:grid-cols-2">
                        <InputGroup id="name" autocomplete="organization" label="Nome do salão" type="text"
                            placeholder="Entre o nome do salão" v-model="salon.name" :required=true
                            @input="() => validationErrors.name = ''">
                            <IDCardIcon class="fill-current" />
                        </InputGroup>
                        <InputGroup id="phone" autocomplete="tel-local" label="Numero de telefone" type="tel"
                            placeholder="Entre o numero de telephone do salão" v-model="salon.phone"
                            :error="validationErrors.phone" :required=true
                            @input="event => handlePhoneChange(event.target.value)">
                            <PhoneIcon class="fill-current" />
                        </InputGroup>
                        <InputGroup id="address" autocomplete="address-level1" label="Adresso postal" type="text"
                            placeholder="Entre o adresso do salão" v-model="salon.address"
                            :error="validationErrors.address" :required=true
                            @input="() => validationErrors.address = ''">
                            <PostalAddressIcon class="fill-current" />
                        </InputGroup>
                        <InputGroup id="postalCode" autocomplete="postal-code" label="Codigo Postal" type="text"
                            placeholder="Entre o codigo postal" v-model="salon.postalCode"
                            :error="validationErrors.postalCode" :required=true
                            @input="() => validationErrors.postalCode = ''">
                            <MailboxIcon class="fill-current" />
                        </InputGroup>
                        <InputGroup id="city" autocomplete="address-level2" label="Cidade" type="text"
                            placeholder="Entre o nome da cidade" v-model="salon.city" :error="validationErrors.city"
                            :required=true @input="() => validationErrors.city = ''">
                            <CityIcon class="fill-current" />
                        </InputGroup>
                        <InputGroup id="country" autocomplete="country-name" label="Pais" type="text"
                            placeholder="Entre o nome do pais" v-model="salon.country" :error="validationErrors.country"
                            :required=true :disabled=true>
                            <FlagIcon class="fill-current" />
                        </InputGroup>
                    </div>
                    <div class="mt-6">
                        <DefaultButton value="Registar o salão" />
                    </div>
                </form>
            </div>
        </DefaultCard>
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref, watchEffect } from 'vue';
import { useRoute, useRouter } from 'vue-router';
import { storeToRefs } from 'pinia';

import { api } from '@/api';
import { validateSalonData } from '@/utils/validators/salons';
import { useSalonsStore } from '@/stores/salons';

import type { SalonCreatePayload, SalonUpdatePayload } from '@/types/salons';
import type { SalonGeneralInformationValidation } from '@/types/validators';

import DefaultCard from '@/components/Cards/DefaultCard.vue';
import InputGroup from '@/components/Forms/InputGroup.vue';
import DefaultButton from '@/components/Buttons/DefaultButton.vue';
import IDCardIcon from '@/components/Icons/IDCardIcon.vue';
import PhoneIcon from '@/components/Icons/PhoneIcon.vue';
import PostalAddressIcon from '@/components/Icons/PostalAddressIcon.vue';
import CityIcon from '@/components/Icons/CityIcon.vue';
import FlagIcon from '@/components/Icons/FlagIcon.vue';
import MailboxIcon from '@/components/Icons/MailboxIcon.vue';

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
    validationErrors.value = validateSalonData(salon.value);

    if (Object.values(validationErrors.value).some((error) => error !== '')) {
        return;
    }

    try {
        if (isEdit) {
            await api().salons.update({ id: Number(route.params.id), ...salon.value });
            router.push({ name: 'getSalon', params: { id: route.params.id } });
        } else {
            const response = await api().salons.create(salon.value);
            router.push({ name: 'handleBusinessHours', params: { id: response.data.id.toString() } });
        }
    } catch (error) {
        console.log(error);
    }
};

const formatPhone = (phone: string) => {
    const cleanedPhone = phone.replace('+351', '').trim();
    const formattedPhone = cleanedPhone.replace(/(\d{3})(\d{3})(\d{3})/, '$1 $2 $3');
    return ((formattedPhone.length > 0 ? '+351 ' : '') + formattedPhone).trim();
};

const handlePhoneChange = (newValue: string) => {
    validationErrors.value.phone = '';

    salon.value.phone = formatPhone(salon.value.phone);
};

onMounted(() => {
    if (isEdit) {
        salonsStore.getSalon({ id: Number(route.params.id) });
    }

});

watchEffect(() => {
    if (isEdit && getterSalon.value) {
        salon.value.name = getterSalon.value.name ?? '';
        salon.value.phone = formatPhone(getterSalon.value.phone ?? '');
        salon.value.address = getterSalon.value.address ?? '';
        salon.value.postalCode = getterSalon.value.postalCode ?? '';
        salon.value.city = getterSalon.value.city ?? '';
        salon.value.country = getterSalon.value.country ?? '';
    }
});
</script>