<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Civility extends Model
{
    use HasFactory;

    public const MAN = 1;
    public const WOMAN = 2;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['short_name', 'long_name'];

    public $timestamps = false;

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
