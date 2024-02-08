<?php

namespace App\Models;

use App\Enums\SnsServices;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SnsLinks extends Model
{
    protected $fillable = [
        'sns_message_id',
        'service',
        'url',
    ];

    protected $casts = [
        'service' => SnsServices::class,
    ];

    public function snsMessage(): BelongsTo
    {
        return $this->belongsTo(SnsMessage::class);
    }
}
