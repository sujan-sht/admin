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
import Textarea from 'primevue/textarea';
import InputNumber from 'primevue/inputnumber';
import Select from 'primevue/select';
import ToggleSwitch from 'primevue/toggleswitch';
import Message from 'primevue/message';

import { Form } from '@primevue/forms';

const toast = useToast();


const props = defineProps({
    category: {
        type: Object,
        default: () => ({}) // Use a function for default object
    },
    categories: {
        type: Object,
        default: () => ({}) // Use a function for default object
    },
    models:{
        type: Array,
        default: () => [] // Use a function for default array
    }
});

const isCategoryEmpty = !props.category || Object.keys(props.category).length === 0;

const initialValues = reactive({
    name: props.category?.name || '',
    slug: props.category?.slug || '',
    excerpt: props.category?.excerpt || '',
    parent_id: props.category?.parent_id || null,
    model: props.category?.model || '',
    active: props.category?.active ?? true,
    featured: props.category?.featured ?? false,
    position: props.category?.position || 1,
    icon: props.category?.icon || '',
    color: props.category?.color || '',
    meta_name: props.category?.meta_name || '',
    meta_description: props.category?.meta_description || '',
    meta_keywords: props.category?.meta_keywords || '',
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
                    summary: `Category ${isCategoryEmpty ? 'created' : 'updated'} successfully!`,
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
        const request = !isCategoryEmpty
            ? router.put(route('categories.update', props.category), values, callbacks)
            : router.post(route('categories.store'), values, callbacks);

        return request;



    }
};
const activePageLabel = !isCategoryEmpty ? 'Edit' : 'Create';
const url = !isCategoryEmpty ? route('categories.edit', props.category) : route('categories.create');


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
                <Form v-slot="$form" :initialValues :resolver @submit="onFormSubmit"
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

                    <div class="flex flex-col gap-1 md:col-span-2">
                        <label class="font-medium">Excerpt</label>
                        <Textarea name="excerpt" rows="4" style="resize: none" placeholder="Short description" />
                    </div>



                    <div class="flex flex-col gap-1">
                        <label class="font-medium">Model</label>
                        <Select name="model" :options="props.models" option-label="label" option-value="value"
                            filter placeholder="Select Model" />
                    </div>
                    <div class="flex flex-col gap-1">
                        <label class="font-medium">Position</label>
                        <InputNumber name="position" />
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="font-medium">Active</label>
                        <ToggleSwitch name="active" :modelValue="!!props.category.active"/>

                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="font-medium">Featured</label>
                        <ToggleSwitch name="featured" :modelValue="!!props.category.featured"/>
                    </div>



                    <div class="flex flex-col gap-1">
                        <label class="font-medium">Icon</label>
                        <InputText name="icon" placeholder="e.g. fa fa-tag" />
                    </div>

                    <div class="flex flex-col gap-1">
                        <label class="font-medium">Color</label>
                        <InputText name="color" placeholder="e.g. #FF0000" />
                    </div>

                    <div class="flex flex-col gap-1 md:col-span-2">
                        <label class="font-medium">Meta Name</label>
                        <InputText name="meta_name" placeholder="Enter meta title" />
                    </div>

                    <div class="flex flex-col gap-1 md:col-span-2">
                        <label class="font-medium">Meta Description</label>
                        <Textarea name="meta_description" rows="3" placeholder="Enter meta description" />
                    </div>

                    <div class="flex flex-col gap-1 md:col-span-2">
                        <label class="font-medium">Meta Keywords</label>
                        <Textarea name="meta_keywords" rows="3"
                            placeholder="Enter meta keywords, separated by commas" />
                    </div>

                    <div class="md:col-span-2 flex justify-center mt-4">
                        <Button type="submit" severity="success" :label="activePageLabel" />
                    </div>
                </Form>
                <!-- <Form v-slot="$form" :initialValues :resolver @submit="onFormSubmit"
                    class="grid grid-cols-1 md:grid-cols-2 gap-6 w-full md:w-[800px]">
                    <div class="flex flex-col gap-1">
                        <label for="name" class="font-medium">Name</label>
                        <InputText name="name" type="text" placeholder="Enter Category Name" fluid />
                        <Message v-if="$form.name?.invalid" severity="error" size="small" variant="simple">{{
                            $form.name.error?.message }}</Message>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label for="excerpt" class="font-medium">Excerpt</label>
                        <Textarea name="excerpt" rows="5" cols="30" placeholder="Enter Excerpt"
                            style="resize: none" />
                        <Message v-if="$form.excerpt?.invalid" severity="error" size="small" variant="simple">{{
                            $form.excerpt.error?.message }}</Message>
                    </div>
                     <div class="md:col-span-2 flex justify-center">
                        <Button type="submit" severity="success" :label="activePageLabel" />
                    </div>
                </Form> -->
            </div>

        </div>
    </AppLayout>
</template>
