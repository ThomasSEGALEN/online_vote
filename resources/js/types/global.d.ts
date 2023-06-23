import type { Page, PageProps } from "@inertiajs/core";
import { Civility, Group, Permission, Role } from "@/types/types";

declare module "@inertiajs/core" {
    interface PageProps {
        flash: {
            success: string;
            error: string;
        };
        data: {
            permissions: Array<Permission>;
        };
        auth: {
            user: {
                id: number;
                first_name: string;
                last_name: string;
                email: string;
            };
            civility: Array<Civility>;
            role: Array<Role>;
            groups: Array<Group>;
            permissions: Array<Permission>;
        };
    }
}

declare module "@inertiajs/vue3" {
    export function usePage<T extends PageProps>(): Page<T>;
}
