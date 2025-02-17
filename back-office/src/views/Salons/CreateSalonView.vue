<template>
    <div class="container mx-auto">
        <div class="flex justify-between items-center mb-4">
            <h1 class="text-2xl font-semibold">Registar o salão : Informaçoões gerais</h1>
            <p class="font-bold">1 / 2</p>
        </div>
        <DefaultCard cardTitle="Informações do salão">
            <div class="p-8">
                <form @submit.prevent="createSalon">
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
import { ref } from 'vue';

import DefaultCard from '@/components/Cards/DefaultCard.vue';
import InputGroup from '@/components/Forms/InputGroup.vue';
import DefaultButton from '@/components/Buttons/DefaultButton.vue';
import IDCardIcon from '@/components/Icons/IDCardIcon.vue';
import PhoneIcon from '@/components/Icons/PhoneIcon.vue';
import PostalAddressIcon from '@/components/Icons/PostalAddressIcon.vue';
import CityIcon from '@/components/Icons/CityIcon.vue';
import FlagIcon from '@/components/Icons/FlagIcon.vue';
import MailboxIcon from '@/components/Icons/MailboxIcon.vue';
import { validateSalonData } from '@/utils/validators';
import { api } from '@/api';
import type { SalonCreatePayload } from '@/types/salons';

const salon = ref<SalonCreatePayload>(
    {
        name: '',
        phone: '',
        address: '',
        postalCode: '',
        city: '',
        country: 'Portugal'
    }
);

const validationErrors = ref({
    name: '',
    phone: '',
    address: '',
    postalCode: '',
    city: '',
    country: ''
});

const createSalon = async () => {
    let errors = validateSalonData(salon.value);

    validationErrors.value = {
        name: '',
        phone: '',
        address: '',
        postalCode: '',
        city: '',
        country: ''
    };

    if (Object.keys(errors).length > 0) {
        for (let key in errors) {
            const typedKey = key as keyof typeof validationErrors.value;
            validationErrors.value[typedKey] = errors[typedKey] ?? '';
        }
        return;
    }

    try {
        await api().salons.create(salon.value);
        console.log('Salon created successfully');
    } catch (error) {
        console.log(error);
    }
};

const handlePhoneChange = (newValue: string) => {
    validationErrors.value.phone = '';

    const phone = salon.value.phone.replace('+351 ', '');

    const formattedPhone = phone.replace(/(\d{3})(\d{3})(\d{3})/, '$1 $2 $3');

    salon.value.phone = (formattedPhone.length > 0 ? '+351 ' : '') + formattedPhone;
};
</script>