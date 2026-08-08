<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DownloadLog extends Model
{
    protected $fillable = ['user_id', 'model', 'model_id', 'file_name', 'file_type', 'count', 'ip_address'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getDisplayNameAttribute()
    {
        if ($this->file_name) {
            return $this->file_name;
        }

        $class = "App\\Models\\{$this->model}";
        if (class_exists($class)) {
            $related = $class::find($this->model_id);
            return $related->title ?? $related->name ?? "{$this->model} #{$this->model_id}";
        }

        return "{$this->model} #{$this->model_id}";
    }
}
