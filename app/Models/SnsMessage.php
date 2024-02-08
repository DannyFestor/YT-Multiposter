<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SnsMessage extends Model
{
    protected $fillable = [
        'content',
    ];

    public function snsLinks(): HasMany
    {
        return $this->hasMany(SnsLinks::class, 'sns_message_id');
    }
}
