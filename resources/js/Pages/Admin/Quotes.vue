<script setup lang="ts">
import BreezeAuthenticatedLayout from '@/Layouts/Authenticated.vue';
import Pagination from '@/Components/Pagination.vue';
import { Inertia } from '@inertiajs/inertia';
import { Head, Link } from '@inertiajs/inertia-vue3';
import { Trash2, RefreshCw } from 'lucide-vue-next';
import axios from 'axios';
import { route } from 'ziggy-js';

import { IUser, IQuote, IPagination } from '@/interfaces'

const props = defineProps<{
    quotes: IPagination<IQuote>;
    user: IUser;
}>();

async function deleteQuote(quoteId: number) {
    await axios.delete(route('admin.users.quotes.delete'), {
        data: {
            quote_id: quoteId,
            user_id: props.user.id,
        },
    });
    Inertia.reload({ only: ['quotes'] });
}

</script>

<template>
    <Head title="Favorite Quotes" />

    <BreezeAuthenticatedLayout>
        <template #header>
            <div class="grid grid-cols-2 grid-flow-col">
                <h2 class="col-span-2 font-semibold text-xl text-gray-800 leading-tight">
                    {{ user.name }}'s Favorite Quotes
                </h2>
                <Link :href="route('admin.users.quotes', user.id)" :only="['quotes']">
                <RefreshCw />
                </Link>
            </div>
        </template>
        <div class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                <div class="bg-white overflow-hidden shadow-sm sm:rounded-lg">
                    <div class="p-6 bg-white border-b border-gray-200">
                        <div v-for="q in quotes.data" :key="q.id"
                            class="grid grid-cols-2 grid-flow-col p-2 rounded border my-2">
                            <div class="col-span-2">{{ q.quote }} - {{ q.author }}</div>
                            <div>
                                <Trash2 @click="deleteQuote(q.quote_id)" />
                            </div>
                        </div>
                        <div v-if="!quotes.data.length">
                            No data found
                        </div>
                    </div>
                    <div class="p-3 flex justify-center">
                        <Pagination :links="quotes.links" />
                    </div>
                </div>
            </div>
        </div>
    </BreezeAuthenticatedLayout>
</template>
