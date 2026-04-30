<?php

namespace App\Http\Controllers;

use Google\Client;
use Google\Service\Drive;
use Illuminate\Http\Request;

class DriveUploadController extends Controller
{
    private function getDriveService(): Drive
    {
        $client = new Client();
        $client->setClientId(config('filesystems.disks.google_drive.clientId'));
        $client->setClientSecret(config('filesystems.disks.google_drive.clientSecret'));
        $client->refreshToken(config('filesystems.disks.google_drive.refreshToken'));
        return new Drive($client);
    }

    /**
     * Step 1 — Browser এর জন্য Resumable Upload Session URL বানাও
     */
    public function createUploadSession(Request $request)
    {
        $request->validate([
            'filename'  => ['required', 'string', 'max:255'],
            'mime_type' => ['required', 'string'],
            'size'      => ['required', 'integer'],
        ]);

        try {
            $client = new Client();
            $client->setClientId(config('filesystems.disks.google_drive.clientId'));
            $client->setClientSecret(config('filesystems.disks.google_drive.clientSecret'));
            $client->refreshToken(config('filesystems.disks.google_drive.refreshToken'));

            $folderId = config('filesystems.disks.google_drive.folderId');
            $filename = \Str::uuid() . '_' . $request->filename;

            $token = $client->getAccessToken();

            // Resumable upload session manually তৈরি করো
            $response = \Illuminate\Support\Facades\Http::withHeaders([
                'Authorization'           => 'Bearer ' . $token['access_token'],
                'Content-Type'            => 'application/json',
                'X-Upload-Content-Type'   => $request->mime_type,
                'X-Upload-Content-Length' => $request->size,
            ])->post('https://www.googleapis.com/upload/drive/v3/files?uploadType=resumable', [
                'name'    => $filename,
                'parents' => [$folderId],
            ]);

            if (!$response->successful()) {
                \Log::error('Drive session error:', $response->json());
                return response()->json(['error' => 'Could not create upload session.'], 500);
            }

            // Upload URL response header এ থাকে
            $uploadUrl = $response->header('Location');

            if (!$uploadUrl) {
                return response()->json(['error' => 'No upload URL returned.'], 500);
            }

            // File ID টা upload URL থেকে বের করো
            preg_match('/upload_id=([^&]+)/', $uploadUrl, $matches);
            $uploadId = $matches[1] ?? \Str::uuid();

            return response()->json([
                'upload_url' => $uploadUrl,
                'file_name'  => $filename,
                'upload_id'  => $uploadId,
            ]);
        } catch (\Exception $e) {
            \Log::error('Drive session error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function resolveFileId(Request $request)
    {
        $request->validate(['file_name' => ['required', 'string']]);

        try {
            $service = $this->getDriveService();

            $files = $service->files->listFiles([
                'q'      => "name = '{$request->file_name}' and trashed = false",
                'fields' => 'files(id)',
            ]);

            $fileId = $files->getFiles()[0]->getId() ?? null;

            if (!$fileId) {
                return response()->json(['error' => 'File not found on Drive.'], 404);
            }

            return response()->json(['file_id' => $fileId]);
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
    /**
     * Step 2 — Upload শেষে Browser Laravel কে জানায়, DB update হয়
     */
    public function completeUpload(Request $request)
    {
        $request->validate([
            'file_name'  => ['required', 'string'],
            'filename'   => ['required', 'string'],
            'size'       => ['required', 'integer'],
            'mime_type'  => ['required', 'string'],
            'model_type' => ['required', 'string', 'in:campaign,asset'],
            'model_id'   => ['required', 'integer'],
        ]);

        try {
            $client = new Client();
            $client->setClientId(config('filesystems.disks.google_drive.clientId'));
            $client->setClientSecret(config('filesystems.disks.google_drive.clientSecret'));
            $client->refreshToken(config('filesystems.disks.google_drive.refreshToken'));

            $service = new Drive($client);

            $files = $service->files->listFiles([
                'q'      => "name = '{$request->file_name}' and trashed = false",
                'fields' => 'files(id, name)',
            ]);

            $fileId = $files->getFiles()[0]->getId() ?? null;

            if (!$fileId) {
                return response()->json(['error' => 'File not found on Drive.'], 404);
            }

            $models = [
                'campaign' => [\App\Models\Campaign::class, 'file'],
                'asset'    => [\App\Models\Asset::class, 'file_path'],
            ];

            [$modelClass, $field] = $models[$request->model_type];
            $model = $modelClass::findOrFail($request->model_id);

            $model->update([
                $field               => 'drive:' . $fileId,
                'file_original_name' => $request->filename,
                'file_mime_type'     => $request->mime_type,
                'file_size'          => $request->size,
                'upload_status'      => 'completed',
            ]);

            return response()->json(['success' => true]);
        } catch (\Exception $e) {
            \Log::error('Drive complete error: ' . $e->getMessage());
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }
}
