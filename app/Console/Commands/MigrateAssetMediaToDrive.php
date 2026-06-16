<?php

namespace App\Console\Commands;

use App\Models\AssetMedia;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;

class MigrateAssetMediaToDrive extends Command
{
    protected $signature   = 'media:migrate-to-drive {--dry-run : Preview without uploading}';
    protected $description = 'Migrate local asset media images to Google Drive';

    private \Google\Service\Drive $driveService;

    public function handle(): void
    {
        // Google Client setup
        $client = new \Google\Client();
        $client->setClientId(config('filesystems.disks.google_drive.clientId'));
        $client->setClientSecret(config('filesystems.disks.google_drive.clientSecret'));
        $client->refreshToken(config('filesystems.disks.google_drive.refreshToken'));
        $this->driveService = new \Google\Service\Drive($client);

        // Local image গুলো নাও — drive: prefix নেই এমন
        $medias = AssetMedia::where('media_type', 'image')
            ->where('file_path', 'not like', 'drive:%')
            ->where('file_path', 'not like', 'http%')
            ->get();

        if ($medias->isEmpty()) {
            $this->info('No local images found to migrate.');
            return;
        }

        $this->info("Found {$medias->count()} local images to migrate.");

        if ($this->option('dry-run')) {
            $this->warn('DRY RUN — no files will be uploaded.');
        }

        $bar      = $this->output->createProgressBar($medias->count());
        $success  = 0;
        $failed   = 0;
        $skipped  = 0;

        $bar->start();

        foreach ($medias as $media) {
            // Local file আছে কিনা check
            if (!Storage::disk('public')->exists($media->file_path)) {
                $this->newLine();
                $this->warn("File not found locally: {$media->file_path} (ID: {$media->id})");
                $skipped++;
                $bar->advance();
                continue;
            }

            if ($this->option('dry-run')) {
                $this->newLine();
                $this->line("  Would migrate: {$media->file_path}");
                $bar->advance();
                continue;
            }

            try {
                // File content নাও
                $content   = Storage::disk('public')->get($media->file_path);
                $extension = pathinfo($media->file_path, PATHINFO_EXTENSION);
                $filename  = Str::uuid() . '.' . $extension;
                $drivePath = 'assets/media/' . $filename;

                // Drive এ upload
                Storage::disk('google_drive')->put($drivePath, $content);

                // File ID নাও
                $files  = $this->driveService->files->listFiles([
                    'q'      => "name = '{$filename}' and trashed = false",
                    'fields' => 'files(id)',
                ]);
                $fileId = $files->getFiles()[0]->getId() ?? null;

                if (!$fileId) {
                    throw new \Exception("File ID not found on Drive after upload.");
                }

                $oldPath = $media->file_path;

                // DB update
                $media->update(['file_path' => 'drive:' . $fileId]);

                // Local file delete
                Storage::disk('public')->delete($oldPath);

                $success++;
                \Log::info("Migrated: {$oldPath} → drive:{$fileId}");

            } catch (\Exception $e) {
                $this->newLine();
                $this->error("Failed ID {$media->id}: {$e->getMessage()}");
                \Log::error("Migration failed for media ID {$media->id}: {$e->getMessage()}");
                $failed++;
            }

            $bar->advance();
        }

        $bar->finish();
        $this->newLine(2);

        $this->table(['Status', 'Count'], [
            ['✅ Success',  $success],
            ['❌ Failed',   $failed],
            ['⏭ Skipped',  $skipped],
        ]);
    }
}