<template>
  <div class="relative">
    <!-- Container du carousel -->
    <div class="overflow-hidden" ref="carouselContainer">
      <div class="flex transition-transform duration-300 ease-in-out" :style="slideStyle">
        <div v-for="(slide, index) in slides" :key="index" class="w-full flex-shrink-0">
          <slot :name="`slide-${index}`" />
        </div>
      </div>
    </div>

    <!-- Flèches de navigation -->
    <template v-if="showArrows">
      <!-- Flèche précédente -->
      <button @click="previousSlide"
        class="absolute left-4 top-1/2 -translate-y-1/2 p-2 rounded-full bg-white/80 hover:bg-white shadow-md transition-colors"
        :class="{ 'opacity-50 cursor-not-allowed': !loop && currentIndex === 0 }">
        <ChevronLeftIcon class="w-6 h-6 text-dark" />
      </button>

      <!-- Flèche suivante -->
      <button @click="nextSlide"
        class="absolute right-4 top-1/2 -translate-y-1/2 p-2 rounded-full bg-white/80 hover:bg-white shadow-md transition-colors"
        :class="{ 'opacity-50 cursor-not-allowed': !loop && currentIndex === slides.length - 1 }">
        <ChevronRightIcon class="w-6 h-6 text-dark" />
      </button>
    </template>

    <!-- Points de navigation -->
    <div v-if="showDots" class="absolute bottom-4 left-1/2 -translate-x-1/2 flex gap-2">
      <button v-for="(_, index) in slides" :key="index" @click="goToSlide(index)"
        class="w-2 h-2 rounded-full transition-all"
        :class="index === currentIndex ? 'bg-primary w-4' : 'bg-white/60 hover:bg-white/80'">
      </button>
    </div>
  </div>
</template>

<script setup lang="ts">
import { ref, computed, onMounted, onUnmounted } from 'vue';
import { ChevronLeftIcon, ChevronRightIcon } from '@heroicons/vue/24/solid';

interface Props {
  slides: number;
  showArrows?: boolean;
  showDots?: boolean;
  autoplay?: boolean;
  autoplayInterval?: number;
  loop?: boolean;
  effect?: 'slide' | 'fade';
}

const props = withDefaults(defineProps<Props>(), {
  showArrows: true,
  showDots: true,
  autoplay: false,
  autoplayInterval: 5000,
  loop: true,
  effect: 'slide'
});

const currentIndex = ref(0);
const carouselContainer = ref<HTMLElement | null>(null);
let autoplayInterval: NodeJS.Timeout | null = null;

// Style pour l'animation de défilement
const slideStyle = computed(() => {
  if (props.effect === 'fade') {
    return {
      transform: 'none',
      opacity: 1
    };
  }
  return {
    transform: `translateX(-${currentIndex.value * 100}%)`
  };
});

// Navigation
const nextSlide = () => {
  if (currentIndex.value < props.slides - 1) {
    currentIndex.value++;
  } else if (props.loop) {
    currentIndex.value = 0;
  }
};

const previousSlide = () => {
  if (currentIndex.value > 0) {
    currentIndex.value--;
  } else if (props.loop) {
    currentIndex.value = props.slides - 1;
  }
};

const goToSlide = (index: number) => {
  currentIndex.value = index;
};

// Gestion du défilement automatique
const startAutoplay = () => {
  if (props.autoplay && !autoplayInterval) {
    autoplayInterval = setInterval(() => {
      if (currentIndex.value < props.slides - 1) {
        currentIndex.value++;
      } else if (props.loop) {
        currentIndex.value = 0;
      }
    }, props.autoplayInterval);
  }
};

const stopAutoplay = () => {
  if (autoplayInterval) {
    clearInterval(autoplayInterval);
    autoplayInterval = null;
  }
};

// Gestion du swipe sur mobile
let touchStartX = 0;
let touchEndX = 0;

const handleTouchStart = (e: TouchEvent) => {
  touchStartX = e.touches[0].clientX;
};

const handleTouchEnd = (e: TouchEvent) => {
  touchEndX = e.changedTouches[0].clientX;
  handleSwipe();
};

const handleSwipe = () => {
  const swipeThreshold = 50;
  const diff = touchEndX - touchStartX;

  if (Math.abs(diff) > swipeThreshold) {
    if (diff > 0) {
      previousSlide();
    } else {
      nextSlide();
    }
  }
};

// Cycle de vie
onMounted(() => {
  if (carouselContainer.value) {
    carouselContainer.value.addEventListener('touchstart', handleTouchStart);
    carouselContainer.value.addEventListener('touchend', handleTouchEnd);
  }
  startAutoplay();
});

onUnmounted(() => {
  if (carouselContainer.value) {
    carouselContainer.value.removeEventListener('touchstart', handleTouchStart);
    carouselContainer.value.removeEventListener('touchend', handleTouchEnd);
  }
  stopAutoplay();
});
</script>

<style scoped>
.fade-enter-active,
.fade-leave-active {
  transition: opacity 0.3s ease;
}

.fade-enter-from,
.fade-leave-to {
  opacity: 0;
}
</style>