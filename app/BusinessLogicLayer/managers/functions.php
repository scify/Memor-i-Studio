<?php
/**
 * Generates and returns a random string
 *
 * @param int $length the length of the string
 * @return string the random string generated
 */
if (!function_exists('generateRandomString')) {
    function generateRandomString($length = 10) {
        $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
        $charactersLength = strlen($characters);
        $randomString = '';
        for ($i = 0; $i < $length; $i++) {
            $randomString .= $characters[rand(0, $charactersLength - 1)];
        }
        return $randomString;
    }
}

if (!function_exists('milliseconds')) {
    function milliseconds() {
        return round(microtime(true) * 1000);
    }
}