<template>
  <Teleport to="body">
    <Transition enter-active-class="transition duration-300 ease-out" enter-from-class="opacity-0"
      enter-to-class="opacity-100" leave-active-class="transition duration-200 ease-in" leave-from-class="opacity-100"
      leave-to-class="opacity-0">
      <div v-if="modelValue" class="fixed inset-0 z-50 flex bg-black/50 backdrop-blur-sm overflow-scroll"
        :class="modalClasses.position[position]">
        <div ref="modalRef" role="dialog" aria-modal="true"
          class="bg-white rounded-lg shadow-xl transform transition-all w-full" :class="[
            modalClasses.size[size],
            {
              'm-4': size !== 'full',
              'h-full': size === 'full'
            }
          ]">
          <!-- Header -->
          <div v-if="title || showCloseButton" class="flex items-center p-4"
            :class="{ 'justify-end': showCloseButton && !title, 'justify-between': showCloseButton && title }">
            <h2 v-if="title" class="text-xl font-semibold">{{ title }}</h2>
            <button v-if="showCloseButton" @click="$emit('update:modelValue', false)"
              class="text-gray-500 hover:text-gray-700 focus:outline-none cursor-pointer" aria-label="Close modal">
              <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24"
                stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
              </svg>
            </button>
          </div>

          <!-- Content -->
          <div class="p-4 overflow-y-auto" :class="{ 'max-h-[calc(100vh-200px)]': size !== 'full' }">
            <slot>
              <p class="text-gray-500">No content provided</p>
            </slot>
          </div>

          <!-- Footer -->
          <div v-if="$slots.footer" class="p-4 flex gap-4 w-full justify-end space-x-2">
            <slot name="footer" />
          </div>
        </div>
      </div>
    </Transition>
  </Teleport>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { onClickOutside } from '@vueuse/core';

type ModalSize = 'sm' | 'md' | 'lg' | 'xl' | 'full'
type ModalPosition = 'center' | 'top' | 'bottom' | 'right' | 'left'

interface Props {
  modelValue: boolean
  title?: string
  size?: ModalSize
  position?: ModalPosition
  closeOnClickOutside?: boolean
  closeOnEscape?: boolean
  showCloseButton?: boolean
}

const props = withDefaults(defineProps<Props>(), {
  size: 'md',
  position: 'center',
  closeOnClickOutside: true,
  closeOnEscape: true,
  showCloseButton: true
})

const emit = defineEmits<{
  (e: 'update:modelValue', value: boolean): void
}>()

const modalRef = ref<HTMLDivElement | null>(null)

onClickOutside(modalRef, () => {
  emit('update:modelValue', false)
})

// Classes dynamiques pour la modal
const modalClasses = {
  size: {
    sm: 'max-w-sm',
    md: 'max-w-md',
    lg: 'max-w-lg',
    xl: 'max-w-xl',
    full: 'w-full h-full'
  },
  position: {
    center: 'items-center justify-center',
    top: 'items-start justify-center pt-20',
    bottom: 'items-end justify-center pb-20',
    right: 'items-center justify-end',
    left: 'items-center justify-start'
  }
}
</script>
