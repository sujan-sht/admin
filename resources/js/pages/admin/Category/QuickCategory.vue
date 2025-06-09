<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import Button from 'primevue/button';
import { type BreadcrumbItem } from '@/types';
import { useToast } from "primevue/usetoast";
import Toast from 'primevue/toast';
import { reactive } from 'vue';
import InputText from 'primevue/inputtext';
import Select from 'primevue/select';
import Message from 'primevue/message';

import { Form } from '@primevue/forms';

const toast = useToast();


const props = defineProps({
    categories: {
        type: Object,
        default: () => ({}) // Use a function for default object
    }
});



const resolver = ({ values }) => {
    const errors = {};

    if (!values.name) {
        errors.name = [{ message: 'Name is required.' }];
    }

    return {
        values, // (Optional) Used to pass current form values to submit event.
        errors
    };
};

const onFormSubmit = ({ valid, values }) => {
    if (valid) {

        const callbacks = {
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Category Created Successfully',
                    life: 3000
                });
            },
            onError: () => {
                toast.add({
                    severity: 'error',
                    summary: 'Validation failed',
                    detail: 'Please check the form fields.',
                    life: 3000
                });
            }
        };
        const request = router.post(route('categories.store'), values, callbacks);

        return request;

    }
};
const activePageLabel = 'Create';
const url = route('categories.create');


const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('dashboard') },
    { title: 'Category', href: route('categories.index') },
    { title: activePageLabel, href: url },

];
</script>
<template>

    <Head title="Category" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex justify-end">
                <Link :href="route('categories.index')">
                <Button label="Back" severity="info" raised />
                </Link>
            </div>
            <Toast />

            <div class="flex justify-center">
                <Form v-slot="$form" :resolver @submit="onFormSubmit"
                    class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full md:w-[800px]">
                    <div class="flex flex-col gap-1">
                        <label class="font-medium">Name</label>
                        <InputText name="name" placeholder="Enter name" />
                        <Message v-if="$form.name?.invalid" severity="error">{{ $form.name.error.message }}</Message>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="font-medium">Parent Category</label>
                        <Select name="parent_id" :options="props.categories" option-label="name" option-value="id"
                            filter placeholder="Select Parent Category" />
                    </div>

                    <div class="md:col-span-2 flex justify-center mt-4">
                        <Button type="submit" severity="success" :label="activePageLabel" />
                    </div>
                </Form>
            </div>

        </div>
    </AppLayout>
</template>
