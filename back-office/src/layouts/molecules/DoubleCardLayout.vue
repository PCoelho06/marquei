<template>
    <div class="rounded-sm border border-stroke bg-white shadow-xl">
        <div class="flex flex-wrap items-center">
            <div :class="{
                'xl:w-1/2': true,
                'w-full': responsiveHideRight,
                'w-1/2': !responsiveHideRight,
                'hidden border-stroke xl:block xl:border-r-2': responsiveHideLeft,
                'cursor-pointer hover:bg-primary/10 hover:shadow-2xl': clickLeft
            }" @click="clickLeft">
                <slot name="leftCard"></slot>
            </div>
            <div :class="{
                'xl:w-1/2': true,
                'w-full': responsiveHideLeft,
                'w-1/2': !responsiveHideLeft,
                'hidden xl:block': responsiveHideRight,
                'cursor-pointer hover:bg-primary/10 hover:shadow-2xl': clickRight
            }" @click="clickRight">
                <slot name="rightCard"></slot>
            </div>
        </div>
    </div>
</template>

<script setup lang="ts">
withDefaults(defineProps<{
    clickLeft?: () => void
    clickRight?: () => void
    responsiveHideLeft?: boolean
    responsiveHideRight?: boolean
}>(), {
    responsiveHideLeft: false,
    responsiveHideRight: false
});
</script>