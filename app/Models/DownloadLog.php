<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DownloadLog extends Model
{
    protected $fillable = ['user_id', 'model', 'model_id','file_name','file_type', 'count', 'ip_address'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
