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
import { useConfirm } from "primevue/useconfirm";
import ConfirmPopup from 'primevue/confirmpopup';
import Toast from 'primevue/toast';
const toast = useToast();
const confirm = useConfirm();
const form = useForm({});

const { users } = defineProps<{
    users: object
}>();
type Role = {
    name: string;
};

type User = {
    id: number;
    name: string;
    email: string;
    role?: Role[];
};
const onDelete = (event: MouseEvent, data: User) => {
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
            form.delete(route('users.destroy', data));
            toast.add({ severity: 'error', summary: 'Delete', detail: 'User deleted successfully', life: 3000 });
        },
        reject: () => {

        }
    });
};


const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('dashboard') },
    { title: 'Users', href: route('users.index') },
];
</script>

<template>

    <Head title="Users" />
    <Toast />
    <ConfirmPopup></ConfirmPopup>
    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <Link :href="route('users.create')" class="flex justify-end">
            <Button label="Create User" severity="info" raised />
            </Link>
            <DataTable :value="users" paginator showGridlines :rows="10" :rowsPerPageOptions="[5, 10, 20, 50]"
                tableStyle="min-width: 50rem">
                <Column header="#" style="width: 3rem;">
                    <template #body="slotProps">
                        {{ slotProps.index + 1 }}
                    </template>
                </Column>
                <Column field="name" header="Name"></Column>
                <Column field="email" header="Email"></Column>
                <Column header="Role">
                    <template #body="{ data }">
                        <div>
                            {{data.roles?.length ? data.roles.map(role => role.name).join(', ') : 'N/A'}}
                        </div>
                    </template>
                </Column>
                <Column header="Action">
                    <template #body="{ data }">
                        <div class="flex items-center gap-2">

                            <Link :href="route('users.edit', data)" class="text-green-500">
                            <SquarePen />
                            </Link>
                            <Button @click="onDelete($event, data)" label="Delete" severity="danger" outlined
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
