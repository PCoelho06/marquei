<template>
    <section
      :class="customClasses" 
      :style="sectionStyle" 
      ref="sectionRef"
    >
      <slot></slot>
    </section>
  </template>
  
  <script setup lang="ts">
  import { ref, computed, onMounted, onUnmounted } from 'vue'
  import { useElementBounding, useWindowSize } from '@vueuse/core'
  
  interface Props {
    animationType?: 'scale' | 'fade' | 'slide';
    customClasses?: string;
  }
  
  const props = withDefaults(defineProps<Props>(), {
    animationType: 'scale',
    customClasses: ''
  })
  
  const sectionRef = ref<HTMLElement | null>(null)
  const { top, bottom } = useElementBounding(sectionRef)
  const { height: windowHeight } = useWindowSize()
  
  const scale = ref(1)
  const opacity = ref(1)
  const translateY = ref(0)
  
  // Déterminez le style CSS à appliquer en fonction du type d'animation
  const sectionStyle = computed(() => {
    const styles: Record<string, string> = {}
    
    if (props.animationType === 'scale') {
      styles.transform = `scale(${scale.value})`
    } else if (props.animationType === 'fade') {
      styles.opacity = `${opacity.value}`
    } else if (props.animationType === 'slide') {
      styles.transform = `translateY(${translateY.value}px)`
    }
    
    // Ajoutez une transition pour rendre l'animation plus fluide
    styles.transition = 'transform 0.05s ease, opacity 0.1s ease'
    
    return styles
  })
  
  // Fonction qui calcule les valeurs d'animation en fonction de la position de l'élément
//   const updateAnimationValues = () => {
//     if (!sectionRef.value) return
    
//     // Si la section est visible dans le viewport
//     if (top.value < windowHeight.value && bottom.value > 0) {
//       // Calcul du facteur d'animation basé sur la position de la section par rapport au centre de l'écran
//       const viewportCenter = windowHeight.value / 2
//       const elementCenter = top.value + (bottom.value - top.value) / 2
//       const distanceFromCenter = Math.abs(elementCenter - viewportCenter)
//       const maxDistance = windowHeight.value / 2
      
//       // Normaliser la distance (0 = au centre, 1 = complètement hors vue)
//       const normalizedDistance = Math.min(distanceFromCenter / maxDistance, 1)
      
//       // Appliquer l'intensité configurée
//       const animationValue = 1 - normalizedDistance
      
//       // Mettre à jour les différentes valeurs d'animation
//       scale.value = props.animationType === 'scale' ? 0.5 + animationValue * 0.5 : 1
//       opacity.value = props.animationType === 'fade' ? animationValue : 1
//       translateY.value = props.animationType === 'slide' ? (1 - animationValue) * 100 : 0
//     }
//   }

const updateAnimationValues = () => {
    if (!sectionRef.value) return
    
    // Si la section est visible dans le viewport
    if (top.value < windowHeight.value && bottom.value > 0) {
      
      // Pour les sections qui n'ont pas encore été complètement visibles
      // Calcul basé sur la position relative dans le viewport
      const viewportCenter = windowHeight.value / 2
      const elementPosition = top.value + (bottom.value - top.value) / 2
      
      // Calcul pour l'animation d'entrée (du haut de la page vers le centre)
      // Plus la section est près du centre, plus l'animation est complète
      let progressToCenter = 0
      
      if (elementPosition > viewportCenter) {
        // La section est en train d'entrer dans la vue (en dessous du centre)
        progressToCenter = 1 - Math.min((elementPosition - viewportCenter) / viewportCenter, 1)
      } else {
        // La section est au-dessus du centre ou au centre
        progressToCenter = 1
      }
      
      // Calculer les valeurs d'animation basées sur le progrès
      scale.value = props.animationType === 'scale' ? 0.5 + progressToCenter * 0.5 : 1
      opacity.value = props.animationType === 'fade' ? progressToCenter : 1
      translateY.value = props.animationType === 'slide' ? (1 - progressToCenter) * 100 : 0
    }
  }
  
  // Gestionnaire d'événement pour le défilement
  const handleScroll = () => {
    window.requestAnimationFrame(updateAnimationValues)
  }
  
  onMounted(() => {
    updateAnimationValues()
    window.addEventListener('scroll', handleScroll, { passive: true })
  })
  
  onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll)
  })
  </script>

  <!-- <template>
    <section 
      :class="customClasses" 
      :style="sectionStyle" 
      ref="sectionRef"
    >
      <slot></slot>
    </section>
  </template>
  
  <script setup lang="ts">
  import { ref, computed, onMounted, onUnmounted } from 'vue'
  import { useElementBounding, useWindowSize } from '@vueuse/core'
  
  interface Props {
    bgColor?: string;
    height?: string;
    padding?: string;
    animationType?: 'scale' | 'fade' | 'slide';
    animationIntensity?: number;
    customClasses?: string;
  }
  
  const props = withDefaults(defineProps<Props>(), {
    bgColor: 'bg-white',
    height: 'h-[600px]',
    padding: 'px-4 py-20',
    animationType: 'scale',
    animationIntensity: 0.5,
    customClasses: ''
  })
  
  const sectionRef = ref<HTMLElement | null>(null)
  const { top, bottom } = useElementBounding(sectionRef)
  const { height: windowHeight } = useWindowSize()
  
  const scale = ref(1)
  const opacity = ref(1)
  const translateY = ref(0)
  
  // Déterminez le style CSS à appliquer en fonction du type d'animation
  const sectionStyle = computed(() => {
    const styles: Record<string, string> = {}
    
    if (props.animationType === 'scale') {
      styles.transform = `scale(${scale.value})`
    } else if (props.animationType === 'fade') {
      styles.opacity = `${opacity.value}`
    } else if (props.animationType === 'slide') {
      styles.transform = `translateY(${translateY.value}px)`
    }
    
    // Ajoutez une transition pour rendre l'animation plus fluide
    styles.transition = 'transform 0.3s ease, opacity 0.3s ease'
    
    return styles
  })
  
  // Fonction qui calcule les valeurs d'animation en fonction de la position de l'élément
  const updateAnimationValues = () => {
    if (!sectionRef.value) return
    
    // Si la section est visible dans le viewport
    if (top.value < windowHeight.value && bottom.value > 0) {
      
      // Pour les sections qui n'ont pas encore été complètement visibles
      // Calcul basé sur la position relative dans le viewport
      const viewportCenter = windowHeight.value / 2
      const elementPosition = top.value + (bottom.value - top.value) / 2
      
      // Calcul pour l'animation d'entrée (du haut de la page vers le centre)
      // Plus la section est près du centre, plus l'animation est complète
      let progressToCenter = 0
      
      if (elementPosition > viewportCenter) {
        // La section est en train d'entrer dans la vue (en dessous du centre)
        progressToCenter = 1 - Math.min((elementPosition - viewportCenter) / viewportCenter, 1)
      } else {
        // La section est au-dessus du centre ou au centre
        progressToCenter = 1
      }
      
      // Appliquer l'intensité configurée au progrès
      progressToCenter = Math.min(1, progressToCenter / (1 - props.animationIntensity))
      
      // Calculer les valeurs d'animation basées sur le progrès
      scale.value = props.animationType === 'scale' ? 0.5 + progressToCenter * 0.5 : 1
      opacity.value = props.animationType === 'fade' ? progressToCenter : 1
      translateY.value = props.animationType === 'slide' ? (1 - progressToCenter) * 100 : 0
    }
  }
  
  // Gestionnaire d'événement pour le défilement
  const handleScroll = () => {
    window.requestAnimationFrame(updateAnimationValues)
  }
  
  onMounted(() => {
    updateAnimationValues()
    window.addEventListener('scroll', handleScroll, { passive: true })
  })
  
  onUnmounted(() => {
    window.removeEventListener('scroll', handleScroll)
  })
  </script> -->