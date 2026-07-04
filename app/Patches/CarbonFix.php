<?php

namespace App\Patches;

class CarbonFix
{
    private static ?string $patchedCode = null;

    public static function patch(): void
    {
        if (PHP_VERSION_ID < 80400) {
            return;
        }

        $file = __DIR__.'/../../vendor/nesbot/carbon/src/Carbon/Traits/Creator.php';

        if (!file_exists($file)) {
            return;
        }

        $code = file_get_contents($file);

        if (str_contains($code, '$lastErrors === false')) {
            return;
        }

        if (!str_contains($code, 'function setLastErrors(array $lastErrors)')) {
            return;
        }

        $patched = str_replace(
            'function setLastErrors(array $lastErrors)',
            'function setLastErrors($lastErrors)',
            $code
        );

        $patched = str_replace(
            'static::$lastErrors = $lastErrors;',
            'static::$lastErrors = \is_array($lastErrors) ? $lastErrors : [\'warning_count\' => 0, \'warnings\' => [], \'error_count\' => 0, \'errors\' => []];',
            $patched
        );

        if ($patched === $code) {
            return;
        }

        if (is_writable($file)) {
            file_put_contents($file, $patched);
            if (function_exists('opcache_invalidate')) {
                opcache_invalidate($file, true);
            }
            return;
        }

        self::$patchedCode = $patched;
    }

    public static function registerClassMap(\Composer\Autoload\ClassLoader $loader): void
    {
        if (self::$patchedCode === null) {
            return;
        }

        $loader->addClassMap([
            'Carbon\\Traits\\Creator' => '',
        ]);

        spl_autoload_register([self::class, 'autoload'], true, true);
    }

    public static function autoload(string $class): void
    {
        if ($class !== 'Carbon\\Traits\\Creator') {
            return;
        }

        $code = preg_replace('/^<\?php\s*/', '', self::$patchedCode);
        eval($code);
    }
}
