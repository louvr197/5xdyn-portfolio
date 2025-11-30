<script setup lang="ts">
import { Briefcase } from 'lucide-vue-next';
</script>

<template>
    <div
        class="flex aspect-square size-8 items-center justify-center rounded-md bg-gradient-to-br from-primary to-primary/80 text-white shadow-sm"
    >
        <Briefcase class="size-4" />
    </div>
    <div class="ml-1 grid flex-1 text-left text-sm">
        <span class="mb-0.5 truncate leading-tight font-semibold"
            >Portfolio Manager</span
        >
    </div>
</template>
