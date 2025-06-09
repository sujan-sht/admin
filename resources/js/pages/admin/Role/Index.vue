<script setup lang="ts">
import AppLayout from '@/layouts/AppLayout.vue';
import { Head, useForm } from '@inertiajs/vue3';
import DataTable from 'primevue/datatable';
import Column from 'primevue/column';
import { Link } from '@inertiajs/vue3';
import Button from 'primevue/button';
import { SquarePen, Trash2, Eye } from 'lucide-vue-next';
import { type BreadcrumbItem } from '@/types';
import { useToast } from "primevue/usetoast";
import { useConfirm } from "primevue/useconfirm";
import ConfirmPopup from 'primevue/confirmpopup';
import Toast from 'primevue/toast';
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
            form.delete(route('roles.destroy', data));
            toast.add({ severity: 'error', summary: 'Delete', detail: 'Role deleted successfully', life: 3000 });
        },
        reject: () => {

        }
    });
};


const { roles } = defineProps({
    roles: {
        type: Array,
        default: []
    }
});

const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('dashboard') },
    { title: 'Roles', href: route('roles.index') },
];
</script>

<template>

    <Head title="Roles" />

    <AppLayout :breadcrumbs="breadcrumbs">
        <Toast />
        <ConfirmPopup></ConfirmPopup>

        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <div class="flex justify-end">
                <Link :href="route('roles.create')">
                <Button label="Create Role" severity="info" raised />
                </Link>
            </div>

            <DataTable :value="roles" paginator :rows="10" :rowsPerPageOptions="[5, 10, 20, 50]"
                tableStyle="min-width: 50rem">
                <Column header="#" style="width: 3rem;">
                    <template #body="slotProps">
                        {{ slotProps.index + 1 }}
                    </template>
                </Column>
                <Column field="name" header="Name"></Column>
                <Column field="description" header="Description"></Column>
                <Column header="Action">


                    <template #body="{ data }">
                        <div class="flex items-center gap-2">
                            <Link :href="route('roles.show', data)" class="text-orange-500 border-solid">
                            <Eye />
                            </Link>
                            <Link :href="route('roles.edit', data)" class="text-green-500 border-solid">
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
