<?php

namespace Tests\Feature\Concerns;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;

/**
 * File tests write into a temporary storage folder, never into the developer's storage/app.
 */
trait UsesTemporaryStorage {
    private string $temporaryStoragePath;

    protected function useTemporaryStorage(): void {
        $this->temporaryStoragePath = sys_get_temp_dir() . '/memori-test-storage-' . Str::random(12);
        File::ensureDirectoryExists($this->temporaryStoragePath . '/app');
        $this->app->useStoragePath($this->temporaryStoragePath);
        $this->beforeApplicationDestroyed(fn () => File::deleteDirectory($this->temporaryStoragePath));
    }
}
