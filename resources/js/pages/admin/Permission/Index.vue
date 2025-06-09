<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { Link } from '@inertiajs/vue3';
import Button from 'primevue/button';
import { SquarePen, Trash2 } from 'lucide-vue-next';
import { type BreadcrumbItem } from '@/types';
import { useToast } from "primevue/usetoast";
import Toast from 'primevue/toast';
import { useConfirm } from "primevue/useconfirm";
import ConfirmPopup from 'primevue/confirmpopup';
const toast = useToast();
const confirm = useConfirm();
const form = useForm({});

const onDelete = (event, data) => {
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
            toast.add({ severity: 'error', summary: 'Delete', detail: 'Permission deleted successfully', life: 3000 });
        },
        reject: () => {

        }
    });
};

const { permissions } = defineProps({
    permissions: {
        type: Array,
        default: []
    }
});



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
            <div class="flex justify-end">
                <Link :href="route('permissions.create')">
                <Button label="Create Permission" severity="info" raised />
                </Link>
            </div>

            <DataTable :value="permissions" paginator :rows="10" :rowsPerPageOptions="[5, 10, 20, 50]"
                tableStyle="min-width: 50rem">
                <Column header="#" style="width: 3rem;">
                    <template #body="slotProps">
                        {{ slotProps.index + 1 }}
                    </template>
                </Column>
                <Column field="browse" header="Browse"></Column>
                <Column field="read" header="Read"></Column>
                <Column field="edit" header="Edit"></Column>
                <Column field="add" header="Add"></Column>
                <Column field="delete" header="Delete"></Column>
                <Column field="role" header="Role">
                    <template #body="slotProps">
                        {{ slotProps.data.role_name }}
                    </template>
                </Column>
                <Column field="name" header="Name">
                    <template #body="slotProps">
                        {{ slotProps.data.name ?? 'N/A' }}
                    </template>
                </Column>
                <Column field="model" header="Model"></Column>
                <Column field="can" header="Can/Cannot"></Column>

                <Column header="Action">
                    <template #body="{ data }">
                        <div class="flex items-center gap-2">

                            <Link :href="route('permissions.edit', data)" class="text-green-500">
                            <SquarePen />
                            </Link>
                            <Button @click="(event) => onDelete(event, data)" label="Delete" severity="danger" outlined
                                :style="{ border: 'none', padding: 0 }">
                                <Trash2 />
                            </Button>

                        </div>
                    </template>
                </Column>
            </DataTable>
        </div>
    </AppLayout>
</template>
