<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head } from '@inertiajs/vue3';
import { type BreadcrumbItem } from '@/types';
import Toast from 'primevue/toast';
import { useToast } from "primevue/usetoast";

const toast = useToast();


const props = defineProps({
    category: {
        type: Object,
        default: () => ({})
    }
});

console.log(props.category);
const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('dashboard') },
    { title: 'Category', href: route('categories.index') },
    { title: 'Show', href: route('categories.show',props.category.id) },

];
</script>

<template>

    <Head title="Category" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <Toast />
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <h4> This is View Page </h4>
        </div>
    </AppLayout>
</template>
