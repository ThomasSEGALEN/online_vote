import { useForm } from "@inertiajs/vue3";

export default function roleForm(name = "", permissions: object = {}) {
    const form = useForm({
        name: name,
        permissions: permissions,
    });

    return form;
}
