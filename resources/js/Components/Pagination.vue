<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import AngleLeftIcon from "@/Components/AngleLeftIcon.vue";
import AngleRightIcon from "@/Components/AngleRightIcon.vue";

defineProps({
    to: {
        type: Number,
        default: () => 0,
    },
    from: {
        type: Number,
        default: () => 0,
    },
    total: {
        type: Number,
        default: () => 0,
    },
    links: {
        type: Array<Link>,
        default: () => [],
    },
});
</script>
<template>
    <div
        v-if="links!.length - 1 > 2"
        class="flex flex-wrap flex-row items-center justify-between"
    >
        <div>
            <p class="text-sm font-medium leading-5 text-gray-700">
                <span class="font-medium">{{ total ? from : 0 }}</span>
                à
                <span class="font-medium">{{ total ? to : 0 }}</span>
                sur
                <span class="font-medium">{{ total }}</span>
                résultats
            </p>
        </div>

        <div class="flex">
            <template v-for="link in links">
                <template v-if="link.url && !link.active">
                    <Link
                        :key="link.label"
                        :href="link.url"
                        class="relative focus:z-10 inline-flex items-center px-4 py-2 text-sm font-medium leading-5 text-gray-700 bg-white border border-gray-300 hover:text-gray-900 focus:outline-none outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150"
                        :class="{
                            'rounded-l-md': link.label === 'Précédent',
                            'rounded-r-md': link.label === 'Suivant',
                        }"
                        preserve-scroll
                    >
                        <AngleLeftIcon v-if="link.label === 'Précédent'" />
                        <AngleRightIcon v-else-if="link.label === 'Suivant'" />
                        <span v-else>{{ link.label }}</span>
                    </Link>
                </template>

                <template v-else>
                    <span
                        :key="link.label"
                        class="inline-flex items-center px-4 py-2 text-sm font-medium leading-5 text-gray-500 border border-gray-300"
                        :class="{
                            'rounded-l-md': link.label === 'Précédent',
                            'rounded-r-md': link.label === 'Suivant',
                            'bg-gray-100':
                                ['Précédent', 'Suivant'].includes(link.label) ||
                                link.active,
                        }"
                    >
                        <AngleLeftIcon v-if="link.label === 'Précédent'" />
                        <AngleRightIcon v-else-if="link.label === 'Suivant'" />
                        <span v-else>{{ link.label }}</span>
                    </span>
                </template>
            </template>
        </div>
    </div>
</template>
