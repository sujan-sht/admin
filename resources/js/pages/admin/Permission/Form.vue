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
    permission:{
        type: Object,
        default : () => ({})
    },
    roles: {
        type: Array,
        default:() => ([])
    }
});

const isPermissionEmpty = !props.permission || Object.keys(props.permission).length === 0;

const initialValues = reactive({
    name: !isPermissionEmpty ? props.permission.name : '',
    role_id: !isPermissionEmpty ? props.permission.role_id : '',
});

const resolver = ({ values }) => {
    const errors = {};

    if (!values.name) {
        errors.name = [{ message: 'Name is required.' }];
    }
    if (!values.role_id) {
        errors.role_id = [{ message: 'Please select role.' }];
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
                    summary: `Permission ${isPermissionEmpty ? 'created' : 'updated'} successfully!`,
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
        const request = !isPermissionEmpty
            ? router.put(route('permissions.update', props.permission), values, callbacks)
            : router.post(route('permissions.store'), values, callbacks);

        return request;



    }
};
const activePageLabel = !isPermissionEmpty ? 'Edit' : 'Create';
const url = !isPermissionEmpty ? route('permissions.edit', props.permission) : route('permissions.create');


const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('dashboard') },
    { title: 'Permissions', href: route('permissions.index') },
    { title: activePageLabel, href: url },

];
</script>
<template>

    <Head title="Permissions" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <Link :href="route('permissions.index')" class="flex justify-end">
            <Button label="Back" severity="info" raised />
            </Link>
            <Toast />

            <div class="flex justify-center">
                <Form v-slot="$form" :initialValues :resolver @submit="onFormSubmit"
                    class="flex flex-col gap-4 w-full sm:w-56">
                    <div class="flex flex-col gap-1">
                        <label for="name" class="font-medium">Name</label>
                        <InputText name="name" type="text" placeholder="Enter Permission Name" fluid />
                        <Message v-if="$form.name?.invalid" severity="error" size="small" variant="simple">{{
                            $form.name.error?.message }}</Message>
                    </div>
                    <div class="flex flex-col gap-1">
                        <label for="description" class="font-medium">Role</label>
                        <Select name="role_id" :options="props.roles" option-label="name" option-value="id" filter
                            placeholder="Select a Role" class="w-full md:w-56" />
                        <Message v-if="$form.role_id?.invalid" severity="error" size="small" variant="simple">{{
                            $form.role_id.error?.message }}</Message>
                    </div>
                    <div class="flex gap-1">
                        <label for="description" class="font-medium">Can/Cannot : </label>
                        <input type="checkbox" name="can">
                    </div>
                    <Button type="submit" severity="success" :label="activePageLabel" />
                </Form>
            </div>

        </div>
    </AppLayout>
</template>
