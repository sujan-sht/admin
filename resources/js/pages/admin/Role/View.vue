<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router, useForm } from '@inertiajs/vue3';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { reactive,computed } from 'vue';

import Button from 'primevue/button';
import { type BreadcrumbItem } from '@/types';
import Toast from 'primevue/toast';
import Select from 'primevue/select';
import Message from 'primevue/message';
import { useConfirm } from "primevue/useconfirm";
import ConfirmPopup from 'primevue/confirmpopup';
import Dialog from 'primevue/dialog';
import { useToast } from "primevue/usetoast";
import { ref } from 'vue';
import { Form } from '@primevue/forms';
import { Trash2 } from 'lucide-vue-next';



const toast = useToast();
const visible = ref(false);
const confirm = useConfirm();
const form = useForm({});



const props = defineProps({
    role: {
        type: Object,
        default: () => ({})
    },
    modules: {
        type: Object,
        default: () => ({})
    }
});
const initialValues = reactive({
    model: '',
    role_id: props.role.id ?? null,
});


const resolver = ({ values }) => {
    const errors = {};

    if (!values.model || values.model.length === 0) {
        errors.model = [{ message: 'Please select at least one model.' }];
    }
    return {
        values,
        errors
    };
};
const onFormSubmit = ({ valid, values }) => {
    if (valid) {
        const payload = {
            ...values,
            role_id: props.role.id
        };

        const callbacks = {
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Module Permission added Successfully',
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

        return router.post(route('permissions.makeModulePermission'), payload, callbacks);
    }
};
const onDelete = (event,data) => {
    confirm.require({
        target: event.currentTarget,
        message: 'Do you want to delete this record?',
        icon: 'pi pi-info-circle',
        rejectProps: {
            label: 'Cancel',
            severity: 'secondary',
            outlined: true
        },
        acceptProps: {
            label: 'Delete',
            severity: 'danger'
        },
        accept: () => {
            form.delete(route('permissions.destroy', data));
            toast.add({ severity: 'error', summary: 'Delete', detail: 'Module Permission deleted successfully', life: 3000 });
        },
        reject: () => {

        }
    });
};


const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('dashboard') },
    { title: 'Permission', href: route('permissions.index') },
];
</script>

<template>

    <Head title="Permission" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <Toast />
        <ConfirmPopup></ConfirmPopup>
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="card flex justify-between ">
                <h3 class=" inline-block align-middle">All Permissions for {{ props.role.name ?? '' }}</h3>
                <div>
                    <Button label="Create Module Permission" @click="visible = true" />
                    <Dialog v-model:visible="visible" modal header="Create Permission" :style="{ width: '25rem' }">
                        <span class="text-surface-500 dark:text-surface-400 block mb-8">Select Module for making BREAD
                            permissions</span>
                        <div class="card flex justify-center">
                            <Form v-slot="$form" :resolver="resolver" :initialValues="initialValues"
                                @submit="onFormSubmit" class="flex flex-col gap-4 w-full md:w-56">
                                <Select name="model" :options="props.modules"  option-label="label" option-value="value" filter placeholder="Select a Module"
                                    class="w-full md:w-56" />
                                <Message v-if="$form.model?.invalid" severity="error" size="small" variant="simple">{{
                                    $form.model.error?.message }}</Message>

                                <div class="flex justify-end gap-2">
                                    <Button type="button" label="Cancel" severity="secondary"
                                        @click="visible = false"></Button>
                                    <Button type="submit" label="Save"></Button>
                                </div>
                            </Form>
                        </div>

                    </Dialog>
                </div>

            </div>
            <DataTable :value="props.role.permissions" paginator showGridlines :rows="10"
                :rowsPerPageOptions="[5, 10, 20, 50]" tableStyle="min-width: 50rem">
                <Column field="model" header="Module"></Column>
                <Column field="name" header="Name">
                    <template #body="slotProps">
                        {{ slotProps.data.name ?? 'N/A' }}
                    </template>
                </Column>
                <Column field="browse" header="Browse"></Column>
                <Column field="read" header="Read"></Column>
                <Column field="edit" header="Edit"></Column>
                <Column field="add" header="Add"></Column>
                <Column field="delete" header="Delete"></Column>


                <Column header="Action">
                    <template #body="{ data }">
                        <div class="flex items-center gap-2">
                            <div class="card flex justify-center">

                                <div class="card flex flex-wrap gap-2 justify-center">
                                    <Button @click="(event) => onDelete(event, data)" label="Delete" severity="danger" outlined>
                                        <Trash2 />
                                    </Button>

                                </div>
                            </div>
                        </div>
                    </template>
                </Column>
            </DataTable>
        </div>
    </AppLayout>
</template>
