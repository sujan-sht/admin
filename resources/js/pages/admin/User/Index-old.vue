<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';
import DangerButton from '@/Components/DangerButton.vue';

import { Head, useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
// import Swal from 'sweetalert2';


defineProps({
    users: Array
});

const form = useForm({});
const deleteData = async (user) => {
    try {
        const result = await Swal.fire({
            title: 'Are you sure?',
            text: 'You won’t be able to revert this!',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'Yes, delete it!',
            cancelButtonText: 'No, keep it'
        });

        if (result.isConfirmed) {
            form.delete(route('users.destroy', user))
        }
    } catch (error) {
        console.log(error);
        // toast.error('Error while deleting');
    }

}
</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>
        <main class="p-4 md:ml-64 h-screen pt-20">

            <Breadcrumb :home="{ href: route('dashboard'), label: 'Dashboard' }" :items="[
                { label: 'Users', current: true }
            ]">
                <Link :href="route('users.create')"
                    class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                Add User
                </Link>
            </Breadcrumb>

            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">
                <table class="w-full text-sm text-left rtl:text-right text-gray-500 dark:text-gray-400">
                    <thead class="text-xs text-gray-700 uppercase bg-gray-50 dark:bg-gray-700 dark:text-gray-400">
                        <tr>
                            <th scope="col" class="px-6 py-3">
                                S.N
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Name
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Role
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Email
                            </th>
                            <th scope="col" class="px-6 py-3">
                                Action
                            </th>
                        </tr>
                    </thead>
                    <tbody>

                        <tr class="bg-white border-b dark:bg-gray-800 dark:border-gray-700 border-gray-200"
                            v-for="(user, index) in users" :key="user.id">
                            <td class="px-6 py-4">
                                {{ index + 1 }}
                            </td>
                            <th scope="row"
                                class="px-6 py-4 font-medium text-gray-900 whitespace-nowrap dark:text-white">
                                {{ user.name }}
                            </th>

                            <td class="px-6 py-4">
                                {{user.roles.length ? user.roles.map(role => role.name).join(', ') : 'N/A'}}
                            </td>
                            <td class="px-6 py-4">
                                {{ user.email }}
                            </td>
                            <td class="px-6 py-4">
                                <Link :href="route('users.edit', user)"
                                    class="text-white bg-gradient-to-r from-amber-500 via-amber-600 to-amber-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-amber-300 dark:focus:ring-amber-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                                Edit
                                </Link>
                                <DangerButton @click="deleteData(user)">Delete</DangerButton>
                            </td>
                        </tr>

                    </tbody>
                </table>
            </div>

        </main>
    </AuthenticatedLayout>
</template>
