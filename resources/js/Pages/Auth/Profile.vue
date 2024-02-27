<script setup lang="ts">
import { usePage } from '@inertiajs/inertia-vue3';
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import BreezeInput from '@/Components/Input.vue';
import BreezeLabel from '@/Components/Label.vue';
import BreezeButton from '@/Components/Button.vue';
import BreezeValidationErrors from '@/Components/ValidationErrors.vue';
import { Head, useForm } from '@inertiajs/inertia-vue3';
import { ref } from 'vue';
import { route } from 'ziggy-js';
import { ISharedProps } from '@/interfaces'
import { IAuth } from '@/interfaces'

interface ProfileForm {
  name: string;
  email: string;
}

interface PasswordForm {
  password: string;
  password_confirmation: string;
}

const page = usePage<ISharedProps>();

const { name, email } = (page.props.value.auth as IAuth).user;
const profileForm = useForm<ProfileForm>({
  name,
  email,
});

const passwordForm = useForm<PasswordForm>({
  password: "",
  password_confirmation: "",
});

const updateInfo = ref(false);
const updatePassword = ref(false);

const profileUpdate = () => {
  profileForm.post(route('profile.update.info'), {
    onSuccess: () => {
      updateInfo.value = false;
    },
  });
};

const passwordUpdate = () => {
  passwordForm.post(route('profile.update.password'), {
    onSuccess: () => {
      passwordForm.reset('password', 'password_confirmation');
      updatePassword.value = false;
    },
  });
};

</script>

<template>
    <Head title="Profile" />
    <BreezeAuthenticatedLayout>
        <template #header>
            <h2 class="col-span-2 font-semibold text-xl text-gray-800 leading-tight">
                Profile
            </h2>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <BreezeValidationErrors class="mb-4" />

                        <div v-if="page.props.value.flash.success"
                            class="flex items-center p-4 mb-4 text-sm text-green-800 rounded-lg bg-green-50 dark:bg-gray-800 dark:text-green-400"
                            role="alert">
                            <svg class="flex-shrink-0 inline w-4 h-4 me-3" aria-hidden="true"
                                xmlns="http://www.w3.org/2000/svg" fill="currentColor" viewBox="0 0 20 20">
                                <path
                                    d="M10 .5a9.5 9.5 0 1 0 9.5 9.5A9.51 9.51 0 0 0 10 .5ZM9.5 4a1.5 1.5 0 1 1 0 3 1.5 1.5 0 0 1 0-3ZM12 15H8a1 1 0 0 1 0-2h1v-3H8a1 1 0 0 1 0-2h2a1 1 0 0 1 1 1v4h1a1 1 0 0 1 0 2Z" />
                            </svg>
                            <span class="sr-only">Info</span>
                            <div>
                                {{ page.props.value.flash.success }}
                            </div>
                        </div>
                        <form @submit.prevent="profileUpdate">
                            <div class="flex justify-end">
                                <BreezeButton v-if="!updateInfo && !updatePassword" @click="updateInfo = !updateInfo"
                                    class="mr-2">Edit Profile</BreezeButton>
                                <BreezeButton v-if="!updateInfo && !updatePassword"
                                    @click="updatePassword = !updatePassword">Update Password</BreezeButton>
                            </div>
                            <div>
                                <BreezeLabel for="name" value="Name" />
                                <BreezeInput :disabled="!updateInfo" id="name" type="text" class="mt-1 block w-full"
                                    v-model="profileForm.name" required autofocus autocomplete="name" />
                            </div>

                            <div class="mt-4" v-if="!updateInfo">
                                <BreezeLabel for="email" value="Email" />
                                <BreezeInput :disabled="true" id="email" type="email" class="mt-1 block w-full"
                                    v-model="profileForm.email" required autocomplete="username" />
                            </div>

                            <div v-if="updateInfo" class="py-4">
                                <BreezeButton :class="{ 'opacity-25': profileForm.processing }"
                                        :disabled="profileForm.processing">Update Profile</BreezeButton>
                            </div>
                        </form>

                        <div v-if="updatePassword">
                            <form @submit.prevent="passwordUpdate">
                                <div class="mt-4">
                                    <BreezeLabel for="password" value="Password" />
                                    <BreezeInput id="password" type="password" class="mt-1 block w-full"
                                        v-model="passwordForm.password" required autocomplete="new-password" />
                                </div>

                                <div class="mt-4">
                                    <BreezeLabel for="password_confirmation" value="Confirm Password" />
                                    <BreezeInput id="password_confirmation" type="password" class="mt-1 block w-full"
                                        v-model="passwordForm.password_confirmation" required autocomplete="new-password" />
                                </div>

                                <div class="py-4">
                                    <BreezeButton :class="{ 'opacity-25': passwordForm.processing }"
                                        :disabled="passwordForm.processing">Update Password</BreezeButton>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
    </div>
</BreezeAuthenticatedLayout></template>
