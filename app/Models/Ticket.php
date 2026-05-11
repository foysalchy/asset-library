<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\Storage;

class Ticket extends Model
{


    protected $fillable = [
        'user_id',
        'subject',
        'description',
        'image',
        'status'
    ];

    protected $casts = [
        'status' => 'integer',
    ];
    const STATUS_PENDING        = 0;
    const STATUS_OPEN = 1;
    const STATUS_CLOSED      = 2;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function replies()
    {
        return $this->hasMany(TicketReply::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            0 => 'Pending',
            1 => 'Open',
            2 => 'Closed',
            default => 'Unknown'
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            0 => 'green',
            1 => 'yellow',
            2 => 'red',
            default => 'gray'
        };
    }
    public function getImageUrlAttribute(): ?string
    {
        return $this->attributes['image'] ? Storage::url($this->attributes['image']) : null;
    }
}
