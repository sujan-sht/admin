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
    if (!visible.value) {
        toast.add({ severity: 'error', summary: 'Are You Sure Want To Delete?', group: 'bc' });
        visible.value = true;
    }
};

const onDelete = async (data) => {
    const message = form.delete(route('roles.destroy', data));
    console.log(message);
    toast.removeGroup('bc');
    visible.value = false;
}

const onClose = () => {
    visible.value = false;
}

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
        <div class="flex h-full flex-1 flex-col gap-4 rounded-xl p-4">
            <Link :href="route('roles.create')" class="flex justify-end">
                <Button label="Create Role" severity="info"  raised/>
            </Link>
            <DataTable :value="roles" paginator showGridlines :rows="5" :rowsPerPageOptions="[5, 10, 20, 50]"
                tableStyle="min-width: 50rem">
                <Column field="name" header="Name"></Column>
                <Column field="description" header="Description"></Column>
                <Column header="Action">
                    <template #body="{ data }">
                        <div class="flex items-center gap-2">

                            <Link :href="route('roles.edit', data)" class="text-green-500">
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
