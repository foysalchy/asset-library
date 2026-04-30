<?php

namespace App\Http\Controllers;

use App\Helpers\FileUploadHelper;
use App\Models\Asset;
use App\Models\Campaign;
use App\Models\DownloadLog;
use Illuminate\Support\Facades\Log;

class FileController extends Controller
{
    private array $models = [
        'campaign' => [
            'class' => Campaign::class,
            'field' => 'file',
        ],
        'asset' => [
            'class' => Asset::class,
            'field' => 'file_path',
        ],
    ];

    public function stream(string $type, string $id)
{
    if (!array_key_exists($type, $this->models)) {
        abort(404);
    }

    $config = $this->models[$type];
    $model  = $config['class']::findOrFail($id);
    $field  = $config['field'];

    if (empty($model->$field)) {
        abort(404, 'No file attached.');
    }

    $this->logDownload($type, $id);

    return FileUploadHelper::streamFile($model->$field);
}

private function logDownload(string $type, string $id): void
{
    try {
        DownloadLog::updateOrCreate(
            [
                'user_id'  => auth()->id(),
                'model'    => $type,
                'model_id' => $id,
            ],
            [
                'ip_address' => request()->ip(),
            ]
        )->increment('count');
    } catch (\Exception $e) {
        Log::error('Download log error: ' . $e->getMessage());
    }
}
}
