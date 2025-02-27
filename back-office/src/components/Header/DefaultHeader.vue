<template>
    <BaseHeader>
        <nav class="flex justify-between items-center">
            <div class="flex items-center space-x-4 ">
                <router-link :to="{ name: 'Home', hash: '#features' }"
                    class="text-dark hover:text-primary">Funcionalidades</router-link>
                <router-link :to="{ name: 'Home', hash: '#testimonials' }"
                    class="text-dark hover:text-primary">Testemunhos</router-link>
                <OutlineButton value="Entrar" size="sm" @click="router.push({ name: 'Signin' })" />
            </div>
        </nav>
    </BaseHeader>
</template>

<script setup lang="ts">
import { useRouter } from 'vue-router';

import BaseHeader from './BaseHeader.vue';
import OutlineButton from '@/components/Buttons/OutlineButton.vue';

const router = useRouter();
</script>