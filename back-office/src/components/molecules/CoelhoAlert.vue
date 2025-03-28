<template>
    <Transition enter-active-class="transform ease-out duration-300 transition"
        enter-from-class="translate-y-2 opacity-0 sm:translate-y-0 sm:translate-x-2"
        enter-to-class="translate-y-0 opacity-100 sm:translate-x-0" leave-active-class="transition ease-in duration-100"
        leave-from-class="opacity-100" leave-to-class="opacity-0">
        <div v-if="modelValue" :class="[
            'rounded-md p-4',
            variantClasses,
        ]" role="alert">
            <!-- Icône -->
            <div v-if="showIcon" class="absolute left-4 top-4">
            </div>

            <!-- Contenu -->
            <div class="flex justify-between gap-2">
                <component v-if="showIcon" :is="icon" class="h-5 w-5" :class="iconColorClass" aria-hidden="true" />
                <div class="flex-1">
                    <div class="flex">
                        <!-- Titre -->
                        <CoelhoText v-if="title" :color="textColorClass" size="sm" weight="medium" class="mb-1">
                            {{ title }}
                        </CoelhoText>
                    </div>

                    <!-- Message -->
                    <CoelhoText :color="textColorClass" size="sm">
                        <slot>{{ message }}</slot>
                    </CoelhoText>
                </div>

                <!-- Bouton de fermeture -->
                <button v-if="dismissible" type="button" class="ml-4 inline-flex" :class="closeButtonColorClass"
                    @click="dismiss">
                    <span class="sr-only">Fermer</span>
                    <XMarkIcon class="h-5 w-5" aria-hidden="true" />
                </button>
            </div>
        </div>
    </Transition>
</template>

<script setup lang="ts">
import { computed } from 'vue';
import {
    CheckCircleIcon,
    ExclamationTriangleIcon,
    InformationCircleIcon,
    XCircleIcon,
    XMarkIcon,
} from '@heroicons/vue/24/solid';
import CoelhoText from '../atoms/typography/CoelhoText.vue';

type AlertVariant = 'info' | 'success' | 'warning' | 'error';

interface Props {
    modelValue: boolean;
    variant?: AlertVariant;
    title?: string;
    message?: string;
    dismissible?: boolean;
    showIcon?: boolean;
}

const props = withDefaults(defineProps<Props>(), {
    variant: 'info',
    dismissible: true,
    showIcon: true,
});

const emit = defineEmits<{
    (e: 'update:modelValue', value: boolean): void;
}>();

// Icône selon la variante
const icon = computed(() => {
    const icons = {
        info: InformationCircleIcon,
        success: CheckCircleIcon,
        warning: ExclamationTriangleIcon,
        error: XCircleIcon,
    };
    return icons[props.variant];
});

// Classes de style selon la variante
const variantClasses = computed(() => {
    const variants = {
        info: 'bg-blue-50 relative',
        success: 'bg-green-50 relative',
        warning: 'bg-yellow-50 relative',
        error: 'bg-red-50 relative',
    };
    return variants[props.variant];
});

const iconColorClass = computed(() => {
    const colors = {
        info: 'text-blue-400',
        success: 'text-green-400',
        warning: 'text-yellow-400',
        error: 'text-red-400',
    };
    return colors[props.variant];
});

const textColorClass = computed(() => {
    const colors = {
        info: 'primary',
        success: 'success',
        warning: 'warning',
        error: 'danger',
    };
    return colors[props.variant];
});

const closeButtonColorClass = computed(() => {
    const colors = {
        info: 'text-blue-400 hover:text-blue-500',
        success: 'text-green-400 hover:text-green-500',
        warning: 'text-yellow-400 hover:text-yellow-500',
        error: 'text-red-400 hover:text-red-500',
    };
    return colors[props.variant];
});

const dismiss = () => {
    emit('update:modelValue', false);
};
</script>