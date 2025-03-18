<template>
    <form v-if="hasAtLeastOneSalon && isReady" @submit.prevent="manageResource">
        <div class="grid grid-cols-1 gap-8 m-6 sm:grid-cols-2">
            <CoelhoInputGroup v-model="resource.type" label="Tipo de recurso" component="select"
                :error="validationErrors.type" :options="resourceTypesOptions" :required="true" />
            <CoelhoInputGroup v-model="resource.salon" label="Salão" component="select" :searchable="true"
                :error="validationErrors.salon" :options="salonOptions" :required="true" />
            <CoelhoInputGroup v-model="resource.name" label="Nome do recurso" component="input" type="text"
                :error="validationErrors.name" placeholder="Entre o nome do recurso" :required="true" />
        </div>
        <div class="flex justify-center">
            <CoelhoButton type="submit" class="mx-auto" size="lg">
                {{ isEdit ? 'Editar o recurso' : 'Registar o recurso' }}
            </CoelhoButton>
        </div>
    </form>
    <div v-else class="grid grid-cols-1 gap-4 my-8">
        <p>Não existem salões registados. Por favor, registe um salão antes de registar um recurso.</p>
        <CoelhoButton @click="router.push({ name: 'CreateSalon' })">Registar um salão</CoelhoButton>
    </div>
</template>

<script setup lang="ts">
import { onMounted, ref } from 'vue';
import { useRoute, useRouter } from 'vue-router';

import { resourceTypesOptions } from '@/composables/constants';
import { mappers, validators } from '@/utils';

import { useSalonsStore } from '@/stores/salons';
import { useResourcesStore } from '@/stores/resources';

import { CoelhoButton, CoelhoInputGroup } from '@/components';
import type { SelectOption } from '@/types';
import type { ResourceType } from '@/types/resources';

interface Resource {
    type: ResourceType;
    salon: number;
    name: string;
}

const route = useRoute();
const router = useRouter();

const salonsStore = useSalonsStore();
const resourcesStore = useResourcesStore();

const isEdit = route.params.id ? true : false;

const salonOptions = ref<SelectOption[]>();
const hasAtLeastOneSalon = ref(true);
const isReady = ref(false);
const validationErrors = ref({
    type: '',
    salon: '',
    name: '',
})

const resource = ref<Resource>({
    type: 'employee',
    salon: 0,
    name: '',
});

const manageResource = async () => {
    // Vérification si le formulaire est valide
    console.log("🚀 ~ manageResource ~ resource.value:", resource.value)
    validationErrors.value = validators.resources.validateResourceData(resource.value);
    console.log("🚀 ~ manageResource ~ errors:", validationErrors.value)

    if (Object.values(validationErrors.value).some((error) => error !== '')) {
        return;
    }

    try {
        if (isEdit) {
            await resourcesStore.updateResource({ id: Number(route.params.id), ...resource.value });
            router.push({ name: 'ListResources' });
        } else {
            await resourcesStore.createResource(resource.value);
            router.push({ name: 'ListResources' });
        }
    } catch (error) {
        console.log(error);
    }
}

onMounted(async () => {
    await salonsStore.listSalons();
    if (salonsStore.getterSalons && salonsStore.getterSalons.length > 0) {
        salonOptions.value = mappers.mapSalonsToOptions(salonsStore.getterSalons);
    } else {
        hasAtLeastOneSalon.value = false;
    }

    if (isEdit) {
        const resourceToEdit = resourcesStore.getterResources?.find((resource) => resource.id === Number(route.params.id));
        if (resourceToEdit) {
            resource.value = {
                type: resourceToEdit.type,
                salon: resourceToEdit.salon.id,
                name: resourceToEdit.name,
            };
        }
    }

    isReady.value = true;
})
</script>