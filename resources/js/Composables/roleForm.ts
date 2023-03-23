import { useForm } from "@inertiajs/vue3";

export default function roleForm(name = "", permissions: Array<number> = []) {
    const form = useForm({
        name: name,
        permissions: permissions,
    });

    return form;
}
