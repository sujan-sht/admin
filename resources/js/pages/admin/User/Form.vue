<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm, router } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import Button from 'primevue/button';
import { type BreadcrumbItem } from '@/types';
import { useToast } from "primevue/usetoast";
import Toast from 'primevue/toast';
import { ref } from 'vue';
import InputText from 'primevue/inputtext';
import Password from 'primevue/password';
import Message from 'primevue/message';
import MultiSelect from 'primevue/multiselect';



import { Form, FormField } from '@primevue/forms';
import { zodResolver } from '@primevue/forms/resolvers/zod';
import { z } from 'zod';
const toast = useToast();

const initialValues = ref({
    name: '',
    email: '',
    password: '',
    confirm_password: '',
    role: '',
});
const resolver = zodResolver(
    z.object({
        name: z.string().min(1, { message: 'Name is required.' }),
        email: z.string().email("Invalid email address"),
        password: z
            .string()
            .min(3, { message: 'Minimum 3 characters.' })
            .refine((value) => /[a-z]/.test(value), {
                message: 'Must have a lowercase letter.'
            })
            .refine((value) => /[A-Z]/.test(value), {
                message: 'Must have an uppercase letter.'
            })
            .refine((value) => /\d/.test(value), {
                message: 'Must have a number.'
            }),
        confirm_password: z.string().min(1, { message: 'Please confirm your password' }),
        role: z
            .array(z.string())
            .min(1, { message: 'At least one role must be selected' }),
    }).refine((data) => data.password === data.confirm_password, {
        path: ['confirm_password'],
        message: 'Passwords do not match.',
    })
);

const onFormSubmit = (e) => {

    if (e.valid) {
        router.post(route('users.store'));
        toast.add({ severity: 'success', summary: 'Form is submitted.', life: 3000 });
    }
};
const props = defineProps({
    mode: {
        type: String, // 'create' or 'edit'
        required: true,
    },
    user: {
        type: Object,
        default: null,
    },
    roles: {
        type: Array,
        default: null
    }
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('dashboard') },
    { title: 'Users', href: route('users.index') },
    { title: 'Create', href: route('users.create') },

];
</script>

<template>

    <Head title="Users" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <Link :href="route('users.index')" class="flex justify-end">
            <Button label="Back" severity="info" raised />
            </Link>
            <Toast />

            <div class="flex justify-center">
                <Form v-slot="$form" :initialValues :resolver @submit="onFormSubmit"
                    class="flex flex-col gap-4 w-full sm:w-60 justify-center">

                    <div class="flex flex-col gap-1">
                        <FormField v-slot="$field" name="name" initialValue="" class="flex flex-col gap-1">
                            <InputText type="text" placeholder="Name" />
                            <Message v-if="$field?.invalid" severity="error" size="small" variant="simple">{{
                                $field.error?.message }}</Message>
                        </FormField>
                        <!-- <InputText name="name" type="text" placeholder="Name" fluid />
                    <Message v-if="$form.name?.invalid" severity="error" size="small" variant="simple">{{
                        $form.name.error.message }}</Message> -->
                    </div>
                    <div class="flex flex-col gap-1">
                        <InputText name="email" type="email" placeholder="Email Address" fluid />
                        <Message v-if="$form.email?.invalid" severity="error" size="small" variant="simple">{{
                            $form.email.error.message }}</Message>
                    </div>
                    <div class="flex flex-col gap-1">
                        <Password name="password" placeholder="Password" :feedback="false" toggleMask fluid />
                        <Message v-if="$form.password?.invalid" severity="error" size="small" variant="simple">
                            <ul class="my-0 px-4 flex flex-col gap-1">
                                <li v-for="(error, index) of $form.password.errors" :key="index">{{ error.message }}
                                </li>
                            </ul>
                        </Message>
                    </div>
                    <div class="flex flex-col gap-1">
                        <Password name="confirm_password" placeholder="Confirm Password" :feedback="false" toggleMask
                            fluid />
                        <Message v-if="$form.confirm_password?.invalid" severity="error" size="small" variant="simple">
                            <ul class="my-0 px-4 flex flex-col gap-1">
                                <li v-for="(error, index) of $form.confirm_password.errors" :key="index">{{
                                    error.message }}
                                </li>
                            </ul>
                        </Message>
                    </div>


                    <div class="flex flex-col gap-1">
                        <MultiSelect name="role" :options="props.roles.roles" optionLabel="name"
                            placeholder="Select Roles" display="chip" filter fluid />
                        <Message v-if="$form.role?.invalid" severity="error" size="small" variant="simple">
                            {{ $form.role.error.message }}
                        </Message>
                    </div>
                    <Button type="submit" severity="secondary" label="Submit" />
                </Form>
            </div>

        </div>
    </AppLayout>
</template>
