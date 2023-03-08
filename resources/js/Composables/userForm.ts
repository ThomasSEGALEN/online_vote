import { useForm } from "@inertiajs/vue3";

export default function userForm(
    civility = 1,
    last_name = "",
    first_name = "",
    email = "",
    role = 2,
    groups = []
) {
    const form = useForm({
        civility: civility as number,
        last_name: last_name as string,
        first_name: first_name as string,
        email: email as string,
        password: "" as string,
        role: role as number,
        groups: groups as Array<number>,
    });

    return form;
}
