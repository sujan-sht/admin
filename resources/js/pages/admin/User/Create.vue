<script setup>
import AuthenticatedLayout from '@/Layouts/AuthenticatedLayout.vue';
import InputError from '@/Components/InputError.vue';
import Breadcrumb from '@/Components/Breadcrumb.vue';

import InputLabel from '@/Components/InputLabel.vue';
import PrimaryButton from '@/Components/PrimaryButton.vue';
import TextInput from '@/Components/TextInput.vue';
import { Head } from '@inertiajs/vue3';
import { useForm } from '@inertiajs/vue3';
import { Link } from '@inertiajs/vue3';
import Select from '@/Components/Select.vue';

const form = useForm({
    name: '',
    email: '',
    role: '',
    password: '',
    confirm_password: '',
});
const handleSubmit = async () => {
    form.post(route('users.store'));
}

</script>

<template>

    <Head title="Dashboard" />

    <AuthenticatedLayout>

        <main class="p-4 md:ml-64 h-screen pt-20">

            <Breadcrumb :home="{ href: route('dashboard'), label: 'Dashboard' }" :items="[
                { href: route('users.index'), label: 'Users' },
                { label: 'Create', current: true }
            ]">
                <Link :href="route('users.index')"
                    class="text-white bg-gradient-to-r from-blue-500 via-blue-600 to-blue-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-blue-300 dark:focus:ring-blue-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2">
                Back
                </Link>
            </Breadcrumb>
            <div class="relative overflow-x-auto shadow-md sm:rounded-lg">



                <section class="bg-white dark:bg-gray-900">
                    <div class="py-8 px-4 mx-auto max-w-2xl lg:py-16">
                        <!-- <h2 class="mb-4 text-xl font-bold text-gray-900 dark:text-white">Add a new product</h2> -->
                        <form @submit.prevent="handleSubmit">
                            <div class="grid gap-4 sm:grid-cols-2 sm:gap-6">
                                <div class="sm:col-span-2">

                                    <InputLabel for="name" value="Name"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" />

                                    <TextInput id="name" type="text"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        placeholder="Enter Your Name" v-model="form.name" required autofocus
                                        autocomplete="name" />

                                    <InputError class="mt-2" :message="form.errors.name" />
                                </div>
                                <div class="w-full">
                                    <InputLabel for="email" value="Email"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" />

                                    <TextInput id="email" type="text"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        placeholder="Email Address" v-model="form.email" required
                                        autocomplete="email" />

                                    <InputError class="mt-2" :message="form.errors.email" />
                                </div>
                                <div>
                                    <InputLabel for="role" value="Role"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" />
                                    <Select v-model="form.role" :options="[
                                        { value: 1, label: 'TV/Monitors' },
                                        { value: 2, label: 'Admin' }
                                    ]" />

                                </div>
                                <div class="w-full">
                                    <InputLabel for="password" value="Password"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" />

                                    <TextInput id="password" type="password"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        v-model="form.password" required autocomplete="new-password"
                                        placeholder="Enter Password" />

                                    <InputError class="mt-2" :message="form.errors.password" />
                                </div>

                                <div>
                                    <InputLabel for="confirm_password" value="Confirm Password"
                                        class="block mb-2 text-sm font-medium text-gray-900 dark:text-white" />

                                    <TextInput id="confirm_password" type="password"
                                        class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-primary-600 focus:border-primary-600 block w-full p-2.5 dark:bg-gray-700 dark:border-gray-600 dark:placeholder-gray-400 dark:text-white dark:focus:ring-primary-500 dark:focus:border-primary-500"
                                        v-model="form.confirm_password" required autocomplete="new-password"
                                        placeholder="Enter Same Password" />

                                    <InputError class="mt-2" :message="form.errors.confirm_password" />
                                </div>

                            </div>
                            <div class="flex justify-center mt-4">
                                <PrimaryButton
                                    class="text-white bg-gradient-to-r from-green-500 via-green-600 to-green-700 hover:bg-gradient-to-br focus:ring-4 focus:outline-none focus:ring-green-300 dark:focus:ring-green-800 font-medium rounded-lg text-sm px-5 py-2.5 text-center me-2 mb-2 "
                                    :class="{ 'opacity-25': form.processing }" :disabled="form.processing">
                                    Add User
                                </PrimaryButton>

                            </div>

                        </form>
                    </div>
                </section>

            </div>

        </main>
    </AuthenticatedLayout>
</template>
