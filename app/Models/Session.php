<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Session extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'title',
        'description',
        'start_date',
        'end_date'
    ];

    /**
     * User relationship.
     *
     * @return BelongsToMany
     */
    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class);
    }

    // /**
    //  * Vote relationship
    //  *
    //  * @return \Illuminate\Database\Eloquent\Relations\HasMany
    //  */
    // public function votes(): HasMany
    // {
    //     return $this->hasMany(Vote::class);
    // }
}
