<template>
    <div class="relative" ref="target">
        <div class="flex items-center gap-4" @click.prevent="dropdownOpen = !dropdownOpen">
            <span class="hidden text-right lg:block">
                <span class="block text-sm font-medium text-black">Salão Maria</span>
                <span class="block text-xs font-medium">Santo Tirso</span>
            </span>

            <span class="h-12 w-12 rounded-full">
                <img src="@/assets/images/user/user.png" alt="User" />
            </span>

            <DropdownArrowIcon :dropdownOpen />
        </div>

        <div v-show="dropdownOpen"
            class="absolute right-0 mt-4 flex w-62.5 flex-col rounded-sm border border-stroke bg-white shadow-xl">
            <ul class="flex flex-col gap-5 border-b border-stroke px-6 py-7.5">
                <li>
                    <router-link to="/profile"
                        class="flex items-center gap-3.5 text-sm font-medium duration-300 ease-in-out hover:text-primary lg:text-base">
                        <ShopIcon class="fill-current" />
                        Perfil do salão
                    </router-link>
                </li>
                <li>
                    <router-link to="/pages/settings"
                        class="flex items-center gap-3.5 text-sm font-medium duration-300 ease-in-out hover:text-primary lg:text-base">
                        <GearIcon class="fill-current" />
                        Configurações
                    </router-link>
                </li>
            </ul>
            <button
                class="flex items-center gap-3.5 py-4 px-6 text-sm font-medium duration-300 ease-in-out hover:text-primary lg:text-base cursor-pointer"
                @click="logUserOut()">
                <LogoutIcon class="fill-current" />
                Sair
            </button>
        </div>
    </div>
</template>

<script setup lang="ts">
import { ref } from 'vue'
import { useRouter } from 'vue-router'
import { onClickOutside } from '@vueuse/core'

import { useAuthStore } from '@/stores/auth'

import DropdownArrowIcon from '../Icons/DropdownArrowIcon.vue';
import GearIcon from '../Icons/GearIcon.vue';
import LogoutIcon from '../Icons/LogoutIcon.vue';
import ShopIcon from '../Icons/ShopIcon.vue';

const router = useRouter()

const { actionLogout } = useAuthStore()

const target = ref(null)
const dropdownOpen = ref(false)

onClickOutside(target, () => {
    dropdownOpen.value = false
})

const logUserOut = async () => {
    await actionLogout()
    router.push({ name: 'Home' })
}
</script>