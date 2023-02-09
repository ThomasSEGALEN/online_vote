<script setup lang="ts">
import { Link } from "@inertiajs/vue3";
import AngleLeftIcon from "../Components/AngleLeftIcon.vue";
import AngleRightIcon from "../Components/AngleRightIcon.vue";
import DoubleAngleLeftIcon from "../Components/DoubleAngleLeftIcon.vue";
import DoubleAngleRightIcon from "../Components/DoubleAngleRightIcon.vue";

const props = defineProps({
    to: Number,
    from: Number,
    total: Number,
    links: Array<Link>,
});

const currentPage = props.links?.findIndex((element) => element.active);
const lastPage = props.links?.length! - 1;
</script>

<template>
    <div class="flex items-center justify-between">
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
            <Link
                v-if="currentPage !== 1"
                :href="links![1].url"
                class="relative focus:z-10 inline-flex items-center px-2 py-2 text-sm font-medium leading-5 text-gray-700 bg-white border border-gray-300 hover:text-gray-900 focus:outline-none outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 rounded-l-md"
            >
                <DoubleAngleLeftIcon />
            </Link>

            <span
                v-else
                class="relative focus:z-10 inline-flex items-center px-2 py-2 text-sm font-medium leading-5 text-gray-700 bg-gray-100 border border-gray-300 hover:text-gray-900 focus:outline-none outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 rounded-l-md"
            >
                <DoubleAngleLeftIcon />
            </span>

            <template
                v-for="link in links?.filter(
                    (link, index) =>
                        index === 0 ||
                        index === currentPage ||
                        index === lastPage
                )"
            >
                <Link
                    v-if="link.url && !link.active"
                    :href="link.url"
                    class="relative focus:z-10 inline-flex items-center px-2 py-2 text-sm font-medium leading-5 text-gray-700 bg-white border border-gray-300 hover:text-gray-900 focus:outline-none outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150"
                >
                    <AngleLeftIcon v-if="link.label === 'Précédent'" />
                    <AngleRightIcon v-if="link.label === 'Suivant'" />
                </Link>

                <span
                    v-else-if="!['Précédent', 'Suivant'].includes(link.label)"
                    class="inline-flex items-center px-4 py-2 text-sm font-medium leading-5 text-gray-500 border border-gray-300"
                    :class="{
                        'bg-gray-100': ['Précédent', 'Suivant'].includes(
                            link.label
                        ),
                        'bg-white': link.active,
                    }"
                >
                    {{ link.label }}
                </span>

                <div
                    v-else
                    class="inline-flex items-center px-2 py-2 text-sm font-medium leading-5 text-gray-500 border border-gray-300"
                    :class="{
                        'bg-gray-100': ['Précédent', 'Suivant'].includes(
                            link.label
                        ),
                        'bg-white': link.active,
                    }"
                >
                    <AngleLeftIcon v-if="link.label === 'Précédent'" />

                    <AngleRightIcon v-if="link.label === 'Suivant'" />
                </div>
            </template>

            <Link
                v-if="lastPage - 1 !== currentPage"
                :href="links![lastPage - 1].url"
                class="relative focus:z-10 inline-flex items-center px-2 py-2 text-sm font-medium leading-5 text-gray-700 bg-white border border-gray-300 hover:text-gray-900 focus:outline-none outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 rounded-r-md"
            >
                <DoubleAngleRightIcon />
            </Link>

            <span
                v-else
                class="relative focus:z-10 inline-flex items-center px-2 py-2 text-sm font-medium leading-5 text-gray-700 bg-gray-100 border border-gray-300 hover:text-gray-900 focus:outline-none outline-none focus:ring ring-gray-300 focus:border-blue-300 active:bg-gray-100 active:text-gray-700 transition ease-in-out duration-150 rounded-r-md"
            >
                <DoubleAngleRightIcon />
            </span>
        </div>
    </div>
</template>
