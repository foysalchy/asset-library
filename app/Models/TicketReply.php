<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class TicketReply extends Model
{
    protected $fillable = [
        'ticket_id',
        'user_id',
        'message',
        'image',
        'is_admin'
    ];

    protected $casts = [
        'is_admin' => 'boolean',
    ];

    public function ticket()
    {
        return $this->belongsTo(Ticket::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
    public function getImageUrlAttribute(): ?string
    {
        return $this->attributes['image'] ? Storage::url($this->attributes['image']) : null;
    }
}
