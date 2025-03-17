<template>
    <component :is="isLink ? 'a' : isTitle ? `h${titleLevel}` : 'div'" :href="isLink ? href : undefined"
        class="flex justify-center items-center" :class="[
            contrast === 'dark' ? 'text-primary' : 'text-white',
            size === 'sm' ? 'text-3xl font-semibold' :
                size === 'md' ? 'text-5xl font-bold' :
                    'text-7xl font-extrabold',
            customClass
        ]">
        <img v-if="(variant === 'icon' || variant === 'both') && contrast === 'dark'"
            src="@/assets/images/logos/icon.svg" alt="logo Marquei" :class="[
                size === 'sm' ? 'w-10 h-10' :
                    size === 'md' ? 'w-16 h-16' :
                        'w-24 h-24',
                variant === 'both' ? 'mr-2' : ''
            ]" />
        <img v-if="(variant === 'icon' || variant === 'both') && contrast === 'light'"
            src="@/assets/images/logos/icon-white.svg" alt="logo Marquei" :class="[
                size === 'sm' ? 'w-10 h-10' :
                    size === 'md' ? 'w-16 h-16' :
                        'w-24 h-24',
                variant === 'both' ? 'mr-2' : ''
            ]" />

        <span v-if="variant === 'text' || variant === 'both'" class="font-brand font-bold tracking-tighter">
            Marquei
        </span>
    </component>
</template>

<script setup lang="ts">
interface LogoProps {
    contrast?: 'light' | 'dark';
    size?: 'sm' | 'md' | 'lg';
    variant?: 'icon' | 'text' | 'both';
    isLink?: boolean;
    href?: string;
    isTitle?: boolean;
    titleLevel?: 1 | 2 | 3 | 4 | 5 | 6;
    customClass?: string;
}

withDefaults(defineProps<LogoProps>(), {
    contrast: 'dark',
    size: 'md',
    variant: 'both',
    isLink: false,
    href: '#',
    isTitle: false,
    titleLevel: 1,
    customClass: ''
})
</script>