<?php

namespace Tests\Unit;

use Illuminate\Support\Facades\File;
use Tests\TestCase;

class InlineScriptsTest extends TestCase {
    /**
     * Vite loads jQuery in deferred module scripts. A classic inline script runs
     * while the page parses, before jQuery exists, so it must be a module too.
     */
    public function test_inline_scripts_that_use_jquery_are_modules(): void {
        $offenders = [];

        foreach (File::allFiles(resource_path('views')) as $file) {
            preg_match_all('/<script(?<attributes>[^>]*)>(?<body>.*?)<\/script>/s', $file->getContents(), $scripts, PREG_SET_ORDER);

            foreach ($scripts as $script) {
                $isModule = str_contains($script['attributes'], 'type="module"');
                $usesJquery = preg_match('/(^|[^\w.])(\$|jQuery)[(.]/', $script['body']);

                if ($usesJquery && !$isModule) {
                    $offenders[] = $file->getRelativePathname();
                }
            }
        }

        $this->assertSame([], $offenders);
    }
}
