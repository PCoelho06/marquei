<template>
    <div class="grid grid-cols-1 items-end gap-8 m-6 sm:grid-cols-2">
        <CoelhoInput v-model="localResourceException.date" type="date" label="Data da indisponibilidade"
            :placeholder="new Date().toLocaleDateString('pt-PT')" :error="validationErrors.date"
            :leftIcon="CalendarDaysIcon" :required="true" class="col-span-2" />
        <CoelhoCheckbox v-model="isAllDay" variant="switch" size="sm" label="Indisponibilidade de dia inteiro"
            class="col-span-2" />
        <CoelhoInput v-model="localResourceException.startTime" type="time" label="Hora de início"
            :placeholder="new Date().toLocaleTimeString('pt-PT', { hour: '2-digit', minute: '2-digit' })"
            :error="validationErrors.startTime" :leftIcon="ClockIcon" :required="true" :disabled="isDisabled" />
        <CoelhoInput v-model="localResourceException.endTime" type="time" label="Hora de fim"
            :placeholder="new Date().toLocaleTimeString('pt-PT', { hour: '2-digit', minute: '2-digit' })"
            :error="validationErrors.endTime" :leftIcon="ClockIcon" :required="true" :disabled="isDisabled" />
    </div>
</template>

<script setup lang="ts">
import { ref, watch } from 'vue';

import { validators } from '@/utils';

import { CoelhoInput } from '@/components';
import { CalendarDaysIcon, ClockIcon } from '@heroicons/vue/24/solid';
import type { ResourceException, ResourceExceptionCreatePayload, ResourceExceptionUpdatePayload } from '@/types/resource-exceptions';
import CoelhoCheckbox from '@/components/atoms/CoelhoCheckbox.vue';

const props = defineProps<{ resourceId: number, resourceException?: ResourceException }>();
const emits = defineEmits(['submit']);

const isAllDay = ref(false);
const isDisabled = ref(false);
const localResourceException = ref<ResourceExceptionCreatePayload | ResourceExceptionUpdatePayload>({
    ...(props.resourceException && 'id' in props.resourceException ? { id: props.resourceException.id } : {}),
    date: props.resourceException?.date || '',
    startTime: props.resourceException?.startTime || '',
    endTime: props.resourceException?.endTime || '',
    resourceId: props.resourceId,
});
const validationErrors = ref({
    date: '',
    startTime: '',
    endTime: '',
});

const isAllDayChecked = (value: boolean) => {
    if (value) {
        localResourceException.value.startTime = '00:00';
        localResourceException.value.endTime = '23:59';
        isDisabled.value = true;
    } else {
        localResourceException.value.startTime = '';
        localResourceException.value.endTime = '';
        isDisabled.value = false;
    }
};
watch(isAllDay, (value) => {
    isAllDayChecked(value);
});

const submitResourceExceptionForm = () => {
    validationErrors.value = validators.resourceExceptions.validateResourceExceptionsData(localResourceException.value);

    if (Object.values(validationErrors.value).some((error) => error !== '')) {
        return;
    }

    emits('submit', localResourceException.value);
};

defineExpose({ submitResourceExceptionForm });
</script>