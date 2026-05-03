<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Notification extends Model
{
    protected $fillable = ['title', 'message', 'type', 'type_id', 'url', 'read_by'];

    protected $casts = [
        'read_by' => 'array',
    ];

    public function isReadBy($userId): bool
    {
        return in_array($userId, $this->read_by ?? []);
    }

    public function markAsReadBy($userId): void
    {
        $readBy = $this->read_by ?? [];
        if (!in_array($userId, $readBy)) {
            $readBy[] = $userId;
            $this->update(['read_by' => $readBy]);
        }
    }
}
