<template>
    <CenteredLayout>
        <AuthCard subtitle="Comece agora" title="Aceder ao meu salão na Marquei" class="w-8/10 m-auto">
            <LoginForm />
        </AuthCard>
    </CenteredLayout>
</template>

<script setup lang="ts">
import CenteredLayout from '@/layouts/CenteredLayout.vue'

import AuthCard from '@/components/Cards/AuthCard.vue'

import LoginForm from './lib/LoginForm.vue'
</script>