<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Document extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'path',
        'session_id'
    ];

    /**
     * Session relationship.
     *
     * @return BelongsToMany
     */
    public function session(): BelongsToMany
    {
        return $this->belongsToMany(Session::class);
    }
}
