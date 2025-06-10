<script setup lang="ts">
import { router } from '@inertiajs/vue3';
import Button from 'primevue/button';
import { useToast } from "primevue/usetoast";
import InputText from 'primevue/inputtext';
import Select from 'primevue/select';
import Message from 'primevue/message';
import { Form } from '@primevue/forms';

const toast = useToast();
const emit = defineEmits(['close']);

const props = defineProps({
    categories: {
        type: Object,
        default: () => ({}) // Use a function for default object
    }
});



const resolver = ({ values }) => {
    const errors = {};

    if (!values.name) {
        errors.name = [{ message: 'Name is required.' }];
    }

    return {
        values, // (Optional) Used to pass current form values to submit event.
        errors
    };
};

const onFormSubmit = ({ valid, values }) => {
    if (valid) {

        const callbacks = {
            onSuccess: () => {
                toast.add({
                    severity: 'success',
                    summary: 'Category Created Successfully',
                    life: 3000
                });
                emit('close');
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
        const request = router.post(route('categories.quickCategory'), values, callbacks);

        return request;

    }
};
</script>
<template>

    <Form v-slot="$form" :resolver @submit="onFormSubmit">
        <div class="flex items-center gap-4 mb-4">
            <label for="name" class="font-semibold w-34">Name</label>
            <InputText id="name" name="name" autocomplete="off" class="flex-1" />
        </div>
        <Message v-if="$form.name?.invalid" severity="error">{{ $form.name.error.message }}</Message>
        <div class="flex items-center gap-4 mb-8 mt-4">
            <label for="parent_id" class="font-semibold w-34">Parent Category</label>
            <Select id="parent_id" name="parent_id" :options="props.categories" option-label="name" option-value="id"
                filter placeholder="Select Parent Category" class="flex-1" />
        </div>
        <div class="flex justify-end gap-2">
            <Button type="button" label="Cancel" severity="secondary" @click="$emit('close')"></Button>
            <Button type="submit" severity="success" label="Add Category" />
        </div>
    </Form>


</template>
