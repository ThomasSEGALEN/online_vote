import { useForm } from "@inertiajs/vue3";

export default function userForm() {
    const form = useForm({
        civility: 1 as number,
        last_name: "" as string,
        first_name: "" as string,
        email: "" as string,
        password: "" as string,
        role: 2 as number,
        groups: [] as Array<number>,
        permissions: [] as Array<number>,
    });

    return form;
}
