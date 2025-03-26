<template>
  <div :class="cardClasses" class="bg-white" @click="clickable ? emit('click') : null" role="article">
    <img v-if="image" :src="image" :alt="imageAlt || 'Card image'" :class="imageClasses" />

    <div :class="contentClasses">
      <slot name="header">
        <div v-if="title || description">
          <h3 v-if="title" class="text-xl font-semibold text-gray-800 mb-2">
            {{ title }}
          </h3>
          <p v-if="description" class="text-gray-600 text-sm">
            {{ description }}
          </p>
        </div>
      </slot>

      <div class="my-2 flex flex-col">
        <slot>
          <p v-if="!$slots.default" class="text-gray-500 text-sm">
            No content provided
          </p>
        </slot>
      </div>

    </div>
    <div v-if="slots.footer"
      class="mt-auto p-2 border-t bg-whitten border-t-stroke flex flex-col xl:flex-row gap-2 justify-end">
      <slot name="footer">
      </slot>
    </div>
  </div>
</template>

<script setup lang="ts">
import { computed, useSlots } from 'vue'

// Types pour les props
type CardVariant = 'vertical' | 'horizontal'
type CardSize = 'sm' | 'md' | 'lg' | 'full'

interface Props {
  title?: string
  description?: string
  image?: string
  imageAlt?: string
  variant?: CardVariant
  size?: CardSize
  clickable?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  variant: 'vertical',
  size: 'md',
  clickable: false
})

const slots = useSlots()

const emit = defineEmits<{
  (e: 'click'): void
}>()

// Classes dynamiques pour la variante et la taille
const cardClasses = computed(() => [
  'border border-stroke rounded-sm shadow-md',
  {
    // Variantes (vertical/horizontal)
    'flex flex-row': props.variant === 'horizontal',
    'flex flex-col': props.variant === 'vertical',

    // Tailles
    'max-w-xs': props.size === 'sm',
    'max-w-md': props.size === 'md',
    'max-w-lg': props.size === 'lg',
    'w-full': props.size === 'full',

    // Interactivité
    'cursor-pointer hover:shadow-xl transition-shadow': props.clickable,
  }
])

// Classes pour l'image
const imageClasses = computed(() => [
  'object-cover',
  {
    'w-full': props.variant === 'vertical',
    'w-48 h-full': props.variant === 'horizontal'
  }
])

// Classes pour le contenu
const contentClasses = computed(() => [
  'p-4 flex flex-col justify-between',
  {
    'w-full': props.variant === 'vertical',
    'w-full pl-4': props.variant === 'horizontal'
  }
])
</script>
