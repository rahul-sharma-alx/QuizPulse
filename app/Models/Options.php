<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Options extends Model
{
    protected $fillable = [
        'question_id',
        'text',
        'is_correct'
    ];

    protected $casts = [
        'is_correct' => 'boolean'
    ];

    public function question(): BelongsTo
    {
        return $this->belongsTo(Questions::class);
    }
}