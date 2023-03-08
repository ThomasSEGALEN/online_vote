<script setup lang="ts">
import { Link, usePage } from "@inertiajs/vue3";
import { ref } from "vue";
import route from "ziggy-js";
import ResponsiveNavLink from "@/Components/ResponsiveNavLink.vue";

const { user, permissions } = usePage().props?.auth as any;

const showingNavigationDropdown = ref<Boolean>(false);
</script>

<template>
    <div class="flex justify-between h-16">
        <div class="flex items-center border-b px-2 w-full md:hidden">
            <button
                @click="showingNavigationDropdown = !showingNavigationDropdown"
                class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-gray-500 hover:bg-gray-100 focus:outline-none outline-none focus:bg-gray-100 focus:text-gray-500 transition duration-150 ease-in-out"
            >
                <svg
                    class="h-6 w-6"
                    stroke="currentColor"
                    fill="none"
                    viewBox="0 0 24 24"
                >
                    <path
                        :class="{
                            hidden: showingNavigationDropdown,
                            'inline-flex': !showingNavigationDropdown,
                        }"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M4 6h16M4 12h16M4 18h16"
                    />
                    <path
                        :class="{
                            hidden: !showingNavigationDropdown,
                            'inline-flex': showingNavigationDropdown,
                        }"
                        stroke-linecap="round"
                        stroke-linejoin="round"
                        stroke-width="2"
                        d="M6 18L18 6M6 6l12 12"
                    />
                </svg>
            </button>
            <div class="flex items-center justify-start px-5 h-14">
                <h1 class="text-lg font-bold">Vote électronique</h1>
            </div>
        </div>
    </div>

    <div
        :class="{
            block: showingNavigationDropdown,
            hidden: !showingNavigationDropdown,
        }"
        class="md:hidden"
    >
        <div class="pt-2 pb-3 space-y-1">
            <ResponsiveNavLink
                v-if="permissions
                        .find(
                            (permission: Permission) =>
                                permission.name === 'viewAnyUsers'
                        )"
                :href="route('users.index')"
                :active="route().current('users.*')"
            >
                Utilisateurs
            </ResponsiveNavLink>
            <ResponsiveNavLink
                v-if="permissions
                        .find(
                            (permission: Permission) =>
                                permission.name === 'viewAnyRoles'
                        )"
                :href="route('roles.index')"
                :active="route().current('roles.*')"
            >
                Rôles
            </ResponsiveNavLink>
            <ResponsiveNavLink
                v-if="permissions
                        .find(
                            (permission: Permission) =>
                                permission.name === 'viewAnyGroups'
                        )"
                :href="route('groups.index')"
                :active="route().current('groups.*')"
            >
                Groupes
            </ResponsiveNavLink>
            <ResponsiveNavLink
                v-if="permissions
                        .find(
                            (permission: Permission) =>
                                permission.name === 'viewAnySessions'
                        )"
                :href="route('sessions.index')"
                :active="route().current('sessions.*')"
            >
                Séances
            </ResponsiveNavLink>
        </div>

        <div class="pt-4 pb-1 border-t border-gray-200">
            <div class="px-4">
                <div class="font-medium text-base text-gray-800">
                    {{ user.first_name }}
                    {{ user.last_name }}
                </div>
                <div class="font-medium text-sm text-gray-500">
                    {{ user.email }}
                </div>
            </div>

            <div class="mt-3 space-y-1 border-b">
                <ResponsiveNavLink :href="route('profile.edit')">
                    Profil
                </ResponsiveNavLink>
                <ResponsiveNavLink
                    :href="route('logout')"
                    method="post"
                    as="button"
                >
                    Déconnexion
                </ResponsiveNavLink>
            </div>
        </div>
    </div>

    <div
        class="hidden min-h-screen md:flex flex-col flex-shrink-0 antialiased bg-gray-50 text-gray-800"
    >
        <div
            class="fixed flex flex-col top-0 left-0 w-64 bg-white h-full border-r"
        >
            <div class="flex items-center justify-start px-5 h-14">
                <h1 class="text-lg font-bold">Vote électronique</h1>
            </div>

            <div
                class="flex flex-grow flex-col justify-between overflow-y-auto overflow-x-hidden"
            >
                <ul class="flex flex-col py-4 space-y-1">
                    <li
                        v-if="permissions
                                .find(
                                    (permission: Permission) =>
                                        permission.name === 'viewAnyUsers'
                                )"
                    >
                        <ResponsiveNavLink
                            :href="route('users.index')"
                            :active="route().current('users.*')"
                        >
                            <div class="flex items-center">
                                <svg
                                    class="w-5 h-5"
                                    fill="#4B5563"
                                    stroke="currentColor"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 448 512"
                                >
                                    <path
                                        d="M304 128a80 80 0 1 0 -160 0 80 80 0 1 0 160 0zM96 128a128 128 0 1 1 256 0A128 128 0 1 1 96 128zM49.3 464H398.7c-8.9-63.3-63.3-112-129-112H178.3c-65.7 0-120.1 48.7-129 112zM0 482.3C0 383.8 79.8 304 178.3 304h91.4C368.2 304 448 383.8 448 482.3c0 16.4-13.3 29.7-29.7 29.7H29.7C13.3 512 0 498.7 0 482.3z"
                                    />
                                </svg>
                                <span
                                    class="ml-2 text-sm tracking-wide truncate"
                                    >Utilisateurs</span
                                >
                            </div>
                        </ResponsiveNavLink>
                    </li>
                    <li
                        v-if="permissions
                                .find(
                                    (permission: Permission) =>
                                        permission.name === 'viewAnyRoles'
                                )"
                    >
                        <ResponsiveNavLink
                            :href="route('roles.index')"
                            :active="route().current('roles.*')"
                        >
                            <div class="flex items-center">
                                <svg
                                    class="w-5 h-5"
                                    fill="#4B5563"
                                    stroke="currentColor"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 384 512"
                                >
                                    <path
                                        d="M48 80V192c0 2.5 1.2 4.9 3.2 6.4l51.2 38.4c6.8 5.1 10.4 13.4 9.5 21.9L101.5 352H53.2l9.4-85L22.4 236.8C8.3 226.2 0 209.6 0 192V72C0 49.9 17.9 32 40 32H344c22.1 0 40 17.9 40 40V192c0 17.6-8.3 34.2-22.4 44.8L321.4 267l9.4 85H282.5l-10.4-93.3c-.9-8.4 2.7-16.8 9.5-21.9l51.2-38.4c2-1.5 3.2-3.9 3.2-6.4V80H272v24c0 13.3-10.7 24-24 24s-24-10.7-24-24V80H160v24c0 13.3-10.7 24-24 24s-24-10.7-24-24V80H48zm4.7 384H331.3l-16.6-32H69.2L52.7 464zm271.9-80c12 0 22.9 6.7 28.4 17.3l26.5 51.2c3 5.8 4.6 12.2 4.6 18.7c0 22.5-18.2 40.8-40.8 40.8H40.8C18.2 512 0 493.8 0 471.2c0-6.5 1.6-12.9 4.6-18.7l26.5-51.2C36.5 390.7 47.5 384 59.5 384h265zM176 288c-8.8 0-16-7.2-16-16V224c0-17.7 14.3-32 32-32s32 14.3 32 32v48c0 8.8-7.2 16-16 16H176z"
                                    />
                                </svg>
                                <span
                                    class="ml-2 text-sm tracking-wide truncate"
                                    >Rôles</span
                                >
                            </div>
                        </ResponsiveNavLink>
                    </li>
                    <li
                        v-if="permissions
                                .find(
                                    (permission: Permission) =>
                                        permission.name === 'viewAnyGroups'
                                )"
                    >
                        <ResponsiveNavLink
                            :href="route('groups.index')"
                            :active="route().current('groups.*')"
                        >
                            <div class="flex items-center">
                                <svg
                                    class="w-5 h-5"
                                    fill="#4B5563"
                                    stroke="currentColor"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 448 512"
                                >
                                    <path
                                        d="M48 24C48 10.7 37.3 0 24 0S0 10.7 0 24V64 350.5 400v88c0 13.3 10.7 24 24 24s24-10.7 24-24V388l80.3-20.1c41.1-10.3 84.6-5.5 122.5 13.4c44.2 22.1 95.5 24.8 141.7 7.4l34.7-13c12.5-4.7 20.8-16.6 20.8-30V66.1c0-23-24.2-38-44.8-27.7l-9.6 4.8c-46.3 23.2-100.8 23.2-147.1 0c-35.1-17.6-75.4-22-113.5-12.5L48 52V24zm0 77.5l96.6-24.2c27-6.7 55.5-3.6 80.4 8.8c54.9 27.4 118.7 29.7 175 6.8V334.7l-24.4 9.1c-33.7 12.6-71.2 10.7-103.4-5.4c-48.2-24.1-103.3-30.1-155.6-17.1L48 338.5v-237z"
                                    />
                                </svg>
                                <span
                                    class="ml-2 text-sm tracking-wide truncate"
                                    >Groupes</span
                                >
                            </div>
                        </ResponsiveNavLink>
                    </li>
                    <li
                        v-if="permissions
                                .find(
                                    (permission: Permission) =>
                                        permission.name === 'viewAnySessions'
                                )"
                    >
                        <ResponsiveNavLink
                            :href="route('groups.index')"
                            :active="route().current('groups.*')"
                        >
                            <div class="flex items-center">
                                <svg
                                    class="w-5 h-5"
                                    fill="#4B5563"
                                    stroke="currentColor"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 512 512"
                                >
                                    <path
                                        d="M168.2 384.9c-15-5.4-31.7-3.1-44.6 6.4c-8.2 6-22.3 14.8-39.4 22.7c5.6-14.7 9.9-31.3 11.3-49.4c1-12.9-3.3-25.7-11.8-35.5C60.4 302.8 48 272 48 240c0-79.5 83.3-160 208-160s208 80.5 208 160s-83.3 160-208 160c-31.6 0-61.3-5.5-87.8-15.1zM26.3 423.8c-1.6 2.7-3.3 5.4-5.1 8.1l-.3 .5c-1.6 2.3-3.2 4.6-4.8 6.9c-3.5 4.7-7.3 9.3-11.3 13.5c-4.6 4.6-5.9 11.4-3.4 17.4c2.5 6 8.3 9.9 14.8 9.9c5.1 0 10.2-.3 15.3-.8l.7-.1c4.4-.5 8.8-1.1 13.2-1.9c.8-.1 1.6-.3 2.4-.5c17.8-3.5 34.9-9.5 50.1-16.1c22.9-10 42.4-21.9 54.3-30.6c31.8 11.5 67 17.9 104.1 17.9c141.4 0 256-93.1 256-208S397.4 32 256 32S0 125.1 0 240c0 45.1 17.7 86.8 47.7 120.9c-1.9 24.5-11.4 46.3-21.4 62.9zM144 272a32 32 0 1 0 0-64 32 32 0 1 0 0 64zm144-32a32 32 0 1 0 -64 0 32 32 0 1 0 64 0zm80 32a32 32 0 1 0 0-64 32 32 0 1 0 0 64z"
                                    />
                                </svg>
                                <span
                                    class="ml-2 text-sm tracking-wide truncate"
                                    >Séances</span
                                >
                            </div>
                        </ResponsiveNavLink>
                    </li>
                </ul>
                <ul class="flex flex-col py-4 space-y-1">
                    <li class="px-4">
                        <div class="font-medium text-base text-gray-800">
                            {{ user.first_name }}
                            {{ user.last_name }}
                        </div>
                        <div
                            class="font-medium text-sm text-gray-500 pb-2 border-b"
                        >
                            {{ user.email }}
                        </div>
                    </li>
                    <li class="pt-2">
                        <ResponsiveNavLink
                            :href="route('profile.edit')"
                            :active="route().current('profile.*')"
                            edit
                        >
                            <div class="flex items-center">
                                <svg
                                    class="w-5 h-5"
                                    fill="#4B5563"
                                    stroke="currentColor"
                                    xmlns="http://www.w3.org/2000/svg"
                                    viewBox="0 0 576 512"
                                >
                                    <path
                                        d="M512 80c8.8 0 16 7.2 16 16V416c0 8.8-7.2 16-16 16H64c-8.8 0-16-7.2-16-16V96c0-8.8 7.2-16 16-16H512zM64 32C28.7 32 0 60.7 0 96V416c0 35.3 28.7 64 64 64H512c35.3 0 64-28.7 64-64V96c0-35.3-28.7-64-64-64H64zM208 256a64 64 0 1 0 0-128 64 64 0 1 0 0 128zm-32 32c-44.2 0-80 35.8-80 80c0 8.8 7.2 16 16 16H304c8.8 0 16-7.2 16-16c0-44.2-35.8-80-80-80H176zM376 144c-13.3 0-24 10.7-24 24s10.7 24 24 24h80c13.3 0 24-10.7 24-24s-10.7-24-24-24H376zm0 96c-13.3 0-24 10.7-24 24s10.7 24 24 24h80c13.3 0 24-10.7 24-24s-10.7-24-24-24H376z"
                                    />
                                </svg>
                                <span
                                    class="ml-2 text-sm tracking-wide truncate"
                                    >Profil</span
                                >
                            </div>
                        </ResponsiveNavLink>
                    </li>
                    <li>
                        <ResponsiveNavLink
                            :href="route('logout')"
                            method="post"
                            as="button"
                        >
                            <div class="flex items-center">
                                <svg
                                    class="w-5 h-5"
                                    fill="none"
                                    stroke="currentColor"
                                    viewBox="0 0 24 24"
                                    xmlns="http://www.w3.org/2000/svg"
                                >
                                    <path
                                        stroke-linecap="round"
                                        stroke-linejoin="round"
                                        stroke-width="2"
                                        d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1"
                                    ></path>
                                </svg>
                                <span
                                    class="ml-2 text-sm tracking-wide truncate"
                                    >Déconnexion</span
                                >
                            </div>
                        </ResponsiveNavLink>
                    </li>
                </ul>
            </div>
        </div>
    </div>
</template>
