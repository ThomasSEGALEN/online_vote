<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Answer extends Model
{
    use HasFactory;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = ['name', 'color', 'label_set_id'];

    /**
     * Label set relationship
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function labelSet(): BelongsTo
    {
        return $this->belongsTo(LabelSet::class);
    }
}
