<?php

namespace App\Exports;

use App\Models\User;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class UserExport implements FromCollection, WithHeadings
{
    /**
    * @return \Illuminate\Support\Collection
    */
    public function headings(): array
    {
        return [
            'id',
            'last_name',
            'first_name',
            'email',
            'civility_id',
            'role_id',
            'created_at',
            'updated_at'
        ];
    }

    public function collection()
    {
        return User::all();
    }
}
