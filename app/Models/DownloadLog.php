<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DownloadLog extends Model
{
    protected $fillable = ['user_id', 'model', 'model_id', 'count', 'ip_address'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
