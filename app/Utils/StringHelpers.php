<?php

namespace App\Utils;

class StringHelpers {

    public static function startsWith($haystack, $needle): bool {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    public static function endsWith($haystack, $needle): bool {
        $length = strlen($needle);
        if ($length == 0) {
            return true;
        }
        return (substr($haystack, -$length) === $needle);
    }

}
