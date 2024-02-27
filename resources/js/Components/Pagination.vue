<script setup lang="ts">
import { InertiaLink } from '@inertiajs/inertia-vue3';

interface Link {
  url: string | null;
  label: string;
  active?: boolean; // Optional property for active state
}

defineProps<{
  links: Link[];
}>();

</script>

<template>
  <div >
    <div class="flex flex-wrap -mb-1">
      <template v-for="(link, key) in links" :key="key">
        <div v-if="link.url === null" class="mr-1 mb-1 px-4 py-3 text-sm leading-4 text-gray-400 border rounded"
            v-html="link.label" />
        <InertiaLink v-else
          class="mr-1 mb-1 px-4 py-3 text-sm leading-4 border rounded hover:bg-white focus:border-indigo-500 focus:text-indigo-500"
          :class="{ 'bg-white': link.active }" :href="link.url" v-html="link.label" />
      </template>
    </div>
  </div>
</template>
