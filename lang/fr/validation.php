<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted' => ':attribute doit être accepté',
    'accepted_if' => ':attribute doit être accepté lorsque :other est :value',
    'active_url' => ":attribute n'est pas une URL valide",
    'after' => ':attribute doit être une date postérieure à :date',
    'after_or_equal' => ':attribute doit être une date postérieure ou égale à :date',
    'alpha' => ':attribute ne peut contenir que des lettres',
    'alpha_dash' => ':attribute ne doit contenir que des lettres, des chiffres, des tirets et des traits de soulignement',
    'alpha_num' => ':attribute ne doit contenir que des lettres et des chiffres',
    'array' => ':attribute doit être un tableau',
    'ascii' => ":attribute ne doit contenir que des caractères alphanumériques et des symboles d'un seul octet",
    'before' => ':attribute doit être une date antérieure à :date',
    'before_or_equal' => ':attribute doit être une date antérieure ou égale à :date',
    'between' => [
        'array' => ':attribute doit avoir entre :min et :max éléments',
        'file' => ':attribute doit être entre :min et :max kilobytes',
        'numeric' => ':attribute doit être entre :min et :max',
        'string' => ':attribute doit être entre :min et :max caractères',
    ],
    'boolean' => ':attribute doit être vrai ou faux',
    'confirmed' => ':attribute ne correspond pas',
    'current_password' => 'Le mot de passe est incorrect',
    'date' => ":attribute n'est pas une date valide",
    'date_equals' => ':attribute doit être une date égale à :date',
    'date_format' => ':attribute ne correspond pas au format :format',
    'decimal' => ':attribute doit avoir :decimal décimales',
    'declined' => ':attribute doit être refusé',
    'declined_if' => ':attribute doit être refusé lorsque :other est :value',
    'different' => ':attribute et :other doivent être différents',
    'digits' => ':attribute doit avoir :digits chiffres',
    'digits_between' => ':attribute doit avoir entre :min et :max chiffres',
    'dimensions' => ':attribute a des dimensions invalides',
    'distinct' => ':attribute a une valeur duppliquée',
    'doesnt_end_with' => ":attribute ne doit pas finir par l'un des éléments suivants : :values",
    'doesnt_start_with' => ":attribute ne doit pas commencer par l'un des éléments suivants : :values",
    'email' => 'Ce champ doit contenir une adresse e-mail valide',
    'ends_with' => ":attribute doit finir par l'un des éléments suivants : :values",
    'enum' => ':attribute sélectionné est invalide',
    'exists' => ':attribute sélectionné est invalide',
    'file' => ':attribute doit être un fichier',
    'filled' => ':attribute doit avoir une valeur',
    'gt' => [
        'array' => ':attribute doit avoir plus de :value éléments',
        'file' => ':attribute doit être supérieur à :value kilobytes',
        'numeric' => ':attribute doit être supérieur à :value',
        'string' => ':attribute doit être supérieur à :value caractères',
    ],
    'gte' => [
        'array' => ':attribute doit avoir :value élements ou plus',
        'file' => ':attribute doit être supérieur ou égal à :value kilobytes',
        'numeric' => ':attribute doit être supérieur ou égal à :value',
        'string' => ':attribute doit être supérieur ou égal à :value caractères',
    ],
    'image' => ':attribute doit être une image',
    'in' => ':attribute sélectionné est invalide',
    'in_array' => ":attribute n'existe pas dans :other",
    'integer' => ':attribute doit être un entier',
    'ip' => ':attribute doit être une adresse IP valide',
    'ipv4' => ':attribute doit être une adresse IPv4 valide',
    'ipv6' => ':attribute doit être une adresse IPv6 valide',
    'json' => ':attribute doit être un JSON valide',
    'lowercase' => ':attribute doit être en minuscules',
    'lt' => [
        'array' => ':attribute doit avoir moins de :value éléments',
        'file' => ':attribute doit être inférieur à :value kilobytes',
        'numeric' => ':attribute doit être inférieur à :value',
        'string' => ':attribute doit être inférieur à :value caractères',
    ],
    'lte' => [
        'array' => ':attribute ne doit pas avoir plus de :value éléments',
        'file' => ':attribute doit être inférieur ou égal à :value kilobytes',
        'numeric' => ':attribute doit être inférieur ou égal à :value',
        'string' => ':attribute doit être inférieur ou égal à :value caractères',
    ],
    'mac_address' => ':attribute doit être une adresse MAC valide',
    'max' => [
        'array' => ':attribute ne doit pas avoir plus de :max éléments',
        'file' => ':attribute ne doit pas être supérieur à :max kilobytes',
        'numeric' => ':attribute ne doit pas être supérieur à :max',
        'string' => ':attribute ne doit pas être supérieur à :max caractères',
    ],
    'max_digits' => ':attribute ne doit pas avoir plus de :max chiffres',
    'max_size' => ':attribute ne doivent pas excéder :max_size Mo',
    'mimes' => ':attribute doit être un fichier de type : :value',
    'mimetypes' => ':attribute doit être un fichier de type : :values',
    'min' => [
        'array' => ':attribute doit avoir au moins :min éléments',
        'file' => ":attribute doit être d'au moins :min kilobytes",
        'numeric' => ":attribute doit être d'au moins :min",
        'string' => ":attribute doit être d'au moins :min caractères",
    ],
    'min_digits' => ':attribute doit avoir au moins :min chiffres',
    'multiple_of' => ':attribute doit être un multiple de :value',
    'not_in' => ':attribute sélectionné est invalide',
    'not_regex' => 'Le format de :attribute est invalide',
    'numeric' => ':attribute doit être un nombre',
    'password' => [
        'letters' => ':attribute doit contenir au moins une lettre',
        'mixed' => ':attribute doit contenir au moins une lettre majuscule et minuscule',
        'numbers' => ':attribute doit contenir au moins un nombre',
        'symbols' => ':attribute doit contenir au moins un symbole',
        'uncompromised' => ':attribute donné est apparu dans une fuite de données, veuillez en choisir un autre',
    ],
    'present' => ':attribute doit être présent',
    'prohibited' => ':attribute est interdit',
    'prohibited_if' => ':attribute est interdit lorsque :other est :value',
    'prohibited_unless' => ':attribute est interdit sauf si :other est dans :values',
    'prohibits' => ":attribute interdit :other d'être présent",
    'regex' => 'Le format de :attribute est invalide',
    'required' => 'Champ requis',
    'required_array_keys' => ':attribute doit contenir des entrées pour : :values',
    'required_if' => ':attribute est requis lorsque :other est :value',
    'required_if_accepted' => ':attribute est requis lorsque :other est accepté',
    'required_unless' => ':attribute est requis sauf si :other est dans :values',
    'required_with' => ':attribute est requis lorsque :values est présent',
    'required_with_all' => ':attribute est requis lorsque :values sont présents',
    'required_without' => ":attribute est requis lorsque :values n'est pas présent",
    'required_without_all' => ":attribute est requis lorsqu'aucune des :values n'est présente",
    'same' => ':attribute et :other doivent correspondre',
    'size' => [
        'array' => ':attribute doit contenir :size éléments',
        'file' => ':attribute doit être de :size kilobytes',
        'numeric' => ':attribute doit être de :size',
        'string' => ':attribute doit être de :size caractères',
    ],
    'starts_with' => ":attribute doit commencer par l'un des éléments suivants : :values",
    'string' => ':attribute doit être une chaîne de caractères',
    'timezone' => ':attribute doit être un fuseau horaire valide',
    'unique' => ':attribute a déjà été pris',
    'uploaded' => ":attribute n'a pas pu être téléchargé",
    'uppercase' => ':attribute doit être en majuscules',
    'url' => ':attribute doit être une URL valide',
    'ulid' => ':attribute doit être un ULID valide',
    'uuid' => ':attribute doit être un UUID valide',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [
        'first_name' => 'Le prénom',
        'last_name' => 'Le nom de famille',
        'email' => "L'adresse e-mail",
        'password' => 'Le mot de passe',
        'role' => 'Le rôle',
        'groups' => 'Les groupes',
        'name' => 'Le nom',
        'permissions' => 'Les permissions',
        'users' => 'Les utilisateurs',
        'title' => 'Le titre',
        'description' => 'La description',
        'documents' => 'Les documents',
        'votes.title.*' => 'Le titre',
        'votes.users.*' => 'Les utilisateurs'
    ],

];
