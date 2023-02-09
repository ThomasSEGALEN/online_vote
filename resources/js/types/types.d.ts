declare enum Method {
    GET = "get",
    POST = "post",
    PUT = "put",
    PATCH = "patch",
    DELETE = "delete",
}

declare interface User {
    id?: number;
    last_name?: string;
    first_name?: string;
    email?: string;
    email_verified_at?: Date;
    password?: string;
    remember_token?: string;
    civility_id?: number;
    role_id?: number;
    created_at?: Date;
    updated_at?: Date;
}

declare interface Link {
    active: boolean;
    label: string;
    url: string;
}
