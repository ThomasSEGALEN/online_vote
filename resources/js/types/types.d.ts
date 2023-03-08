declare interface User {
    id?: number;
    last_name?: string;
    first_name?: string;
    email?: string;
    password?: string;
    remember_token?: string;
    civility_id?: number;
    role_id?: number;
    created_at?: Date;
    updated_at?: Date;
}

declare interface Permission {
    id: number;
    name: string;
}

declare interface Group {
    id: number;
    name: string;
}

declare interface Link {
    active: boolean;
    label: string;
    url: string;
}
