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
import { ref } from 'vue';
const toast = useToast();
const visible = ref(false);
const form = useForm({});

const showTemplate = () => {
    // alert(visible.value);
    if (!visible.value) {
        toast.add({ severity: 'error', summary: 'Are You Sure Want To Delete?', group: 'bc' });
        visible.value = true;
    }
};

const onDelete = async (data) => {
    form.delete(route('users.destroy', data))
    toast.removeGroup('bc');
    toast.add({ severity: 'error', summary: 'Role Deleted Successfully', life: 3000 });
    visible.value = false;
}

const onClose = () => {
    visible.value = false;
}

const { users } = defineProps({
    users: {
        type: Array,
        default: []
    }
});



const breadcrumbs: BreadcrumbItem[] = [
    { title: 'Dashboard', href: route('dashboard') },
    { title: 'Users', href: route('users.index') },
];
</script>

<template>

    <Head title="Users" />
        <Toast />

    <AppLayout :breadcrumbs="breadcrumbs">
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <Link :href="route('users.create')" class="flex justify-end">
                <Button label="Create User" severity="info"  raised/>
            </Link>
            <DataTable :value="users" paginator showGridlines :rows="10" :rowsPerPageOptions="[5, 10, 20, 50]"
                tableStyle="min-width: 50rem">
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
                            <div class="card flex justify-center">
                                <Toast position="top-center" group="bc" @close="onClose">
                                    <template #message="slotProps">
                                        <div class="flex flex-col items-center flex-auto">

                                            <div class="font-medium text-lg my-4 text-white">{{
                                                slotProps.message.summary }}</div>
                                            <Button size="small" label="Yes" severity="error"
                                                @click="onDelete(data)"></Button>
                                        </div>
                                    </template>
                                </Toast>
                                <Trash2 @click="showTemplate" style="cursor:pointer" class="text-red-500"/>
                            </div>
                        </div>
                    </template>
                </Column>
            </DataTable>
        </div>
    </AppLayout>
</template>
