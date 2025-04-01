<template>
    <div class="flex justify-center items-center" @click="href && $router.push(href)"
        :class="{ 'cursor-pointer': href }">
        <img v-if="(variant === 'icon' || variant === 'both') && contrast === 'dark'"
            src="@/assets/images/logos/icon.svg" alt="logo Marquei" :class="{
                'size-10': size === 'sm',
                'size-14': size === 'md',
                'size-24': size === 'lg',
                'mr-2': variant === 'both',
            }" />
        <img v-if="(variant === 'icon' || variant === 'both') && contrast === 'light'"
            src="@/assets/images/logos/icon-white.svg" alt="logo Marquei" :class="{
                'size-10': size === 'sm',
                'size-14': size === 'md',
                'size-24': size === 'lg',
                'mr-2': variant === 'both',
            }" />

        <CoelhoHeading v-if="variant === 'text' || variant === 'both'" :logo="true" :level="titleLevel"
            :color="contrast === 'dark' ? 'primary' : 'light'" :withMargin="false"
            :size="size === 'lg' ? 'xl' : size === 'md' ? 'lg' : 'md'">
            Marquei
        </CoelhoHeading>
    </div>
</template>

<script setup lang="ts">
import { CoelhoHeading } from '@/components';

interface LogoProps {
    contrast?: 'light' | 'dark';
    size?: 'sm' | 'md' | 'lg';
    variant?: 'icon' | 'text' | 'both';
    href?: string;
    titleLevel?: 1 | 2 | 3 | 4 | 5 | 6;
}

withDefaults(defineProps<LogoProps>(), {
    contrast: 'dark',
    size: 'md',
    variant: 'both',
    titleLevel: 1,
})
</script>