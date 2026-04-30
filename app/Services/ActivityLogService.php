<?php
// app/Services/ActivityLogService.php

namespace App\Services;

use App\Models\ActivityLog;
use Illuminate\Database\Eloquent\Model;

class ActivityLogService
{
    public function log(
        string $action,
        Model $model,
        string $description,
        ?array $changes = null
    ): void {
        ActivityLog::create([
            'user_id'     => auth()->id(),
            'action'      => $action,
            'model_type'  => class_basename($model),
            'model_id'    => $model->getKey(),
            'description' => $description,
            'changes'     => $changes,
            'ip_address'  => request()->ip(),
        ]);
    }
}