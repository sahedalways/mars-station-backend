<?php

namespace App\Console\Commands;

use App\Models\ServiceProject;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Storage;

class CleanupOrphanedProjectImagesCommand extends Command
{
    protected $signature = 'services:cleanup-orphaned-project-images {--dry-run : List orphaned files without deleting} {--delete : Delete orphaned files}';

    protected $description = 'Find and optionally delete orphaned service project image files not referenced by any DB record';

    public function handle(): int
    {
        $directory = 'services/projects';

        if (! Storage::disk('public')->exists($directory)) {
            $this->info("Directory {$directory} does not exist. Nothing to clean up.");

            return Command::SUCCESS;
        }

        $diskFiles = Storage::disk('public')->files($directory);
        $dbPaths = ServiceProject::whereNotNull('picture_path')->pluck('picture_path')->all();
        $dbPathsSet = array_flip($dbPaths);

        $orphaned = [];
        foreach ($diskFiles as $file) {
            if (! isset($dbPathsSet[$file])) {
                $orphaned[] = $file;
            }
        }

        $this->info("Disk files: " . count($diskFiles));
        $this->info("DB-referenced paths: " . count($dbPaths));
        $this->info("Orphaned files: " . count($orphaned));

        if (empty($orphaned)) {
            $this->newLine();
            $this->info('No orphaned files found.');

            return Command::SUCCESS;
        }

        $this->newLine();

        foreach ($orphaned as $file) {
            $size = Storage::disk('public')->size($file);
            $this->line("  <fg=yellow>{$file}</> ({$this->formatBytes($size)})");
        }

        $this->newLine();

        if ($this->option('delete')) {
            $confirm = $this->confirm("Delete " . count($orphaned) . " orphaned file(s)?", false);
            if (! $confirm) {
                $this->info('Aborted.');

                return Command::SUCCESS;
            }

            $deleted = 0;
            foreach ($orphaned as $file) {
                if (Storage::disk('public')->delete($file)) {
                    $deleted++;
                }
            }

            $this->info("Deleted {$deleted} orphaned file(s).");
        } else {
            $this->info('Dry run — no files deleted. Use --delete to remove orphaned files.');
        }

        return Command::SUCCESS;
    }

    private function formatBytes(int $bytes): string
    {
        $units = ['B', 'KB', 'MB', 'GB'];
        $i = 0;
        $size = (float) $bytes;
        while ($size >= 1024 && $i < count($units) - 1) {
            $size /= 1024;
            $i++;
        }

        return round($size, 1) . ' ' . $units[$i];
    }
}
