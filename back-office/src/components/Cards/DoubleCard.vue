<template>
    <div class="w-8/10 lg:w-7/10 rounded-sm border border-stroke bg-white shadow-xl">
        <div class="flex items-center">
            <div class="w-1/2 cursor-pointer hover:bg-blue-50 hover:shadow-2xl" @click="$emit('clickLeft')">
                <div class="p-14 text-center">
                    <slot name="leftCard"></slot>
                </div>
            </div>
            <div class="w-1/2 cursor-pointer hover:bg-blue-50 hover:shadow-2xl" @click="$emit('clickRight')">
                <div class="p-14 text-center">
                    <slot name="rightCard"></slot>
                </div>
            </div>
        </div>
    </div>
</template>