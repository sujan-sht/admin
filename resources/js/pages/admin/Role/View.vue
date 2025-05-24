<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, router } from '@inertiajs/vue3';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { Link } from '@inertiajs/vue3';
import { reactive } from 'vue';

import Button from 'primevue/button';
import { Trash2 } from 'lucide-vue-next';
import { type BreadcrumbItem } from '@/types';
import Toast from 'primevue/toast';
import Select from 'primevue/select';
import Message from 'primevue/message';
import ConfirmPopup from 'primevue/confirmpopup';
import { useConfirm } from "primevue/useconfirm";
import { useToast } from "primevue/usetoast";
import Dialog from 'primevue/dialog';
import { ref } from 'vue';
import { Form } from '@primevue/forms';



const toast = useToast();
const visible = ref(false);
const confirm = useConfirm();
// const showTemplate = () => {
//     if (!visible.value) {
//         toast.add({ severity: 'error', summary: 'Are You Sure Want To Delete?', group: 'bc' });
//         visible.value = true;
//     }
// };

// const onDelete = async (data) => {
//     form.delete(route('roles.destroy', data));
//     toast.add({ severity: 'error', summary: 'Role Deleted Successfully', life: 3000 });
//     toast.removeGroup('bc');
//     visible.value = false;
// }

// const onClose = () => {
//     visible.value = false;
// }


const props = defineProps({
    role: {
        type: Array,
        default: () => ({})
    },
    modules: {
        type: Array,
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
const onDelete = (event) => {
    console.log(event);
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
            toast.add({ severity: 'info', summary: 'Confirmed', detail: 'Record deleted', life: 3000 });
        },
        reject: () => {
            toast.add({ severity: 'error', summary: 'Rejected', detail: 'You have rejected', life: 3000 });
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
                                <Select name="model" :options="modules" filter placeholder="Select a Module"
                                    class="w-full md:w-56" />
                                <Message v-if="$form.model?.invalid" severity="error" size="small" variant="simple">{{
                                    $form.model.error?.message }}</Message>
                                <!-- <Select name="model" :options="props.modules" filter optionLabel="name"
                                    placeholder="Select a Country" class="w-full md:w-56">
                                    <template #value="slotProps">
                                        <div v-if="slotProps.value" class="flex items-center">
                                            <img :alt="slotProps.value.label"
                                                src="https://primefaces.org/cdn/primevue/images/flag/flag_placeholder.png"
                                                :class="`mr-2 flag flag-${slotProps.value.code.toLowerCase()}`"
                                                style="width: 18px" />
                                            <div>{{ slotProps.value.name }}</div>
                                        </div>
                                        <span v-else>
                                            {{ slotProps.placeholder }}
                                        </span>
                                    </template>
<template #option="slotProps">
                                        <div class="flex items-center">
                                            <img :alt="slotProps.option.label"
                                                src="https://primefaces.org/cdn/primevue/images/flag/flag_placeholder.png"
                                                :class="`mr-2 flag flag-${slotProps.option.code.toLowerCase()}`"
                                                style="width: 18px" />
                                            <div>{{ slotProps.option.name }}</div>
                                        </div>
                                    </template>
</Select> -->
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
                                <ConfirmPopup></ConfirmPopup>
                                <div class="card flex flex-wrap gap-2 justify-center">
                                    <Button @click="onDelete(data)" label="Delete" severity="danger" outlined></Button>
                                </div>
                                <!-- <Toast position="top-center" group="bc" @close="onClose">
                                    <template #message="slotProps">
                                        <div class="flex flex-col items-center flex-auto">

                                            <div class="font-medium text-lg my-4 text-white">{{
                                                slotProps.message.summary }}</div>
                                            <Button size="small" label="Yes" severity="error"
                                                @click="onDelete(data)"></Button>
                                        </div>
                                    </template>
        </Toast>
        <Trash2 @click="showTemplate" style="cursor:pointer" class="text-red-500" /> -->
                            </div>
                        </div>
                    </template>
                </Column>
            </DataTable>
        </div>
    </AppLayout>
</template>
