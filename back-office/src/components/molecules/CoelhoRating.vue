<template>
  <div class="flex items-center" :class="{ 'cursor-not-allowed opacity-50': disabled }">
    <div class="flex items-center space-x-1" :class="{ 'cursor-pointer': !disabled && !readonly }">
      <template v-for="index in maxStars" :key="index">
        <div class="relative" @mousemove="!disabled && !readonly && handleMouseMove($event, index)"
          @mouseleave="!disabled && !readonly && handleMouseLeave()"
          @click="!disabled && !readonly && handleClick(index)">
          <!-- Étoile de base (vide) -->
          <StarIcon class="w-6 h-6" :class="[
            'transition-colors',
            disabled || readonly ? 'text-gray-300' : 'text-gray-200',
          ]" />

          <!-- Étoile remplie -->
          <div class="absolute inset-0 overflow-hidden" :style="{ width: getStarWidth(index) }">
            <StarIcon class="w-6 h-6" :class="[
              colorClass,
              { 'cursor-pointer': !disabled && !readonly }
            ]" />
          </div>
        </div>
      </template>
    </div>

    <!-- Affichage de la note (optionnel) -->
    <span v-if="showValue" class="ml-2 text-sm text-gray-500">
      {{ displayValue }}
    </span>
  </div>
</template>

<script setup lang="ts">
import { ref, computed } from 'vue';
import { StarIcon } from '@heroicons/vue/24/solid';

interface Props {
  modelValue: number;
  maxStars?: number;
  allowHalf?: boolean;
  readonly?: boolean;
  disabled?: boolean;
  showValue?: boolean;
  color?: 'primary' | 'warning' | 'success';
}

const props = withDefaults(defineProps<Props>(), {
  maxStars: 5,
  allowHalf: true,
  readonly: false,
  disabled: false,
  showValue: false,
  color: 'warning',
});

const emit = defineEmits<{
  (e: 'update:modelValue', value: number): void;
  (e: 'change', value: number): void;
}>();

const hoverValue = ref<number | null>(null);

// Classes de couleur
const colorClass = computed(() => {
  const colors = {
    primary: 'text-primary',
    warning: 'text-yellow-400',
    success: 'text-green-500',
  };
  return colors[props.color];
});

// Valeur à afficher (avec une décimale si demi-étoile)
const displayValue = computed(() => {
  const value = props.modelValue;
  return value % 1 === 0 ? value : value.toFixed(1);
});

// Calcul de la largeur de remplissage de l'étoile
const getStarWidth = (index: number) => {
  const value = hoverValue.value ?? props.modelValue;
  const fullStars = Math.floor(value);
  const decimal = value % 1;

  if (index <= fullStars) {
    return '100%';
  } else if (index === fullStars + 1 && decimal > 0) {
    return `${decimal * 100}%`;
  }
  return '0%';
};

// Gestion des événements
const handleMouseMove = (event: MouseEvent, index: number) => {
  if (props.disabled || props.readonly) return;

  const target = event.currentTarget as HTMLElement;
  const rect = target.getBoundingClientRect();
  const x = event.clientX - rect.left;
  const decimal = x / rect.width;

  if (props.allowHalf && decimal <= 0.5) {
    hoverValue.value = index - 0.5;
  } else {
    hoverValue.value = index;
  }
};

const handleMouseLeave = () => {
  if (props.disabled || props.readonly) return;
  hoverValue.value = null;
};

const handleClick = (index: number) => {
  if (props.disabled || props.readonly) return;

  const newValue = hoverValue.value ?? index;
  emit('update:modelValue', newValue);
  emit('change', newValue);
};
</script>
