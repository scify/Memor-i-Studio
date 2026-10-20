<?php

namespace Tests\Unit;

use App\BusinessLogicLayer\managers\ImgManager;
use App\BusinessLogicLayer\managers\ResourceManager;
use Illuminate\Process\PendingProcess;
use Illuminate\Support\Facades\Process;
use ReflectionMethod;
use Tests\TestCase;

/**
 * Uploaded file names reach external programs. A shell must never parse them.
 */
class ShellCommandTest extends TestCase {
    private const MALICIOUS_NAME = 'song.mp3;touch${IFS}PWNED;#.mp3';

    public function test_stored_file_names_contain_only_safe_characters(): void {
        $slugify = new ReflectionMethod(ResourceManager::class, 'slugifyFileName');

        $fileName = $slugify->invoke(app(ResourceManager::class), 'res_1_abc_' . self::MALICIOUS_NAME);

        $this->assertMatchesRegularExpression('/^[a-z0-9_-]+\.[a-z0-9]+$/', $fileName);
        $this->assertStringEndsWith('.mp3', $fileName);
    }

    public function test_icon_conversion_passes_file_names_as_arguments(): void {
        Process::fake();

        app(ImgManager::class)->covertImgToIco('/tmp', self::MALICIOUS_NAME, 'game_icon.ico');

        Process::assertRan(fn (PendingProcess $process) => $process->command === ['convert', '-resize', 'x64', '-background', 'transparent', self::MALICIOUS_NAME, 'game_icon.ico']
            && $process->path === '/tmp');
    }

    public function test_audio_conversion_passes_file_paths_as_arguments(): void {
        Process::fake();
        $convert = new ReflectionMethod(ResourceManager::class, 'convertFileToMp3AndStore');

        $convert->invoke(app(ResourceManager::class), '/tmp/' . self::MALICIOUS_NAME, '/tmp/out.mp3');

        Process::assertRan(fn (PendingProcess $process) => $process->command === ['./convert_file_to_mp3.sh', '/tmp/' . self::MALICIOUS_NAME, '/tmp/out.mp3']
            && $process->path === public_path());
    }
}
