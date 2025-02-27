<template>
    <header class="sticky top-0 z-999 flex w-full bg-white drop-shadow-1">
        <div class="flex flex-grow items-center justify-between py-4 px-4 shadow-md md:px-6 2xl:px-11">
            <h1 class="text-2xl font-bold text-primary">
                <RouterLink :to="{ name: 'Home' }">💈 Marquei</RouterLink>
            </h1>
            <slot></slot>
        </div>
    </header>
</template>