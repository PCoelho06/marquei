<template>
    <CenteredLayout>
        <AuthCard subtitle="Comece agora" title="Registar o meu salão na Marquei" class="w-8/10 m-auto">
            <RegistrationForm />
        </AuthCard>
    </CenteredLayout>
</template>

<script setup lang="ts">
import CenteredLayout from '@/layouts/CenteredLayout.vue'

import AuthCard from '@/components/Cards/AuthCard.vue'

import RegistrationForm from './lib/RegistrationForm.vue'
</script>