<template>
    <div class="rounded-sm border border-stroke bg-white shadow-xl">
        <div class="flex flex-wrap items-center">
            <div class="hidden w-full xl:block xl:w-1/2">
                <div class="py-17.5 px-26 text-center">
                    <router-link class="mb-5.5 inline-block text-5xl font-bold" to="/">
                        <!-- <img src="@/assets/images/logo/logo-dark.svg" alt="Logo" /> -->
                        💈
                        Marquei
                    </router-link>

                    <p class="font-medium 2xl:px-20">
                        Não espere mais para registrar seu salão e aproveitar nossos serviços.
                    </p>

                    <img src="@/assets/images/illustrations/authentication.svg" alt="illustration" />
                </div>
            </div>
            <div class="w-full border-stroke xl:w-1/2 xl:border-l-2">
                <div class="w-full p-4 sm:p-12.5 xl:p-17.5">
                    <span class="mb-1.5 block font-medium">{{ props.subtitle }}</span>
                    <h2 class="mb-9 text-2xl font-bold text-black sm:text-title-xl2">
                        {{ props.title }}
                    </h2>
                    <slot></slot>
                </div>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
const props = defineProps<{
    subtitle: string
    title: string
}>();
</script>