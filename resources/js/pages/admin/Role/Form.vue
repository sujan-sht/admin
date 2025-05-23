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
import Message from 'primevue/message';
import { Form } from '@primevue/forms';

const toast = useToast();


const props = defineProps({
    role: {
        type: Object,
        default: () => ({}) // Use a function for default object
    }
});
const isRoleEmpty = !props.role.role || Object.keys(props.role.role).length === 0;

const initialValues = reactive({
    name: !isRoleEmpty ? props.role.role.name : '',
    description: !isRoleEmpty ? props.role.role.description : '',
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
                    summary: `User ${isRoleEmpty ? 'created' : 'updated'} successfully!`,
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
        const request = !isRoleEmpty
            ? router.put(route('roles.update', props.role.role), values, callbacks)
            : router.post(route('roles.store'), values, callbacks);

        return request;



    }
};
const activePageLabel = !isRoleEmpty ? 'Edit' : 'Create';
const url = !isRoleEmpty ? route('roles.edit', props.role.role) : route('roles.create');


const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('dashboard') },
    { title: 'Roles', href: route('roles.index') },
    { title: activePageLabel, href: url },

];
</script>
<template>

    <Head title="Roles" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <Link :href="route('roles.index')" class="flex justify-end">
            <Button label="Back" severity="info" raised />
            </Link>
            <Toast />

            <div class="flex justify-center">
                <Form v-slot="$form" :initialValues :resolver @submit="onFormSubmit"
                    class="flex flex-col gap-4 w-full sm:w-56">
                    <div class="flex flex-col gap-1">
                        <label for="name" class="font-medium">Name</label>
                        <InputText name="name" type="text" placeholder="Enter Role Name" fluid />
                        <Message v-if="$form.name?.invalid" severity="error" size="small" variant="simple">{{
                            $form.name.error?.message }}</Message>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label for="description" class="font-medium">Description</label>
                        <Textarea name="description" rows="5" cols="30" placeholder="Enter Description"
                            style="resize: none" />
                        <Message v-if="$form.description?.invalid" severity="error" size="small" variant="simple">{{
                            $form.description.error?.message }}</Message>
                    </div>
                    <Button type="submit" severity="success" :label="activePageLabel" />
                </Form>
            </div>

        </div>
    </AppLayout>
</template>
