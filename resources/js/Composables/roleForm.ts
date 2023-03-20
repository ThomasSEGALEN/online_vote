import { useForm } from "@inertiajs/vue3";

export default function roleForm(name = "", permissions = []) {
    const form = useForm({
        name: name as string,
        permissions: permissions as Array<number>,
    });

    return form;
}
