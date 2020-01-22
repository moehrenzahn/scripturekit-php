<?php

namespace Moehrenzahn\ScriptureKit\Util;

/**
 * Class StringHelper
 *
 * @package Moehrenzahn\ScriptureKit\Util
 */
class StringHelper
{
    public static function contains(string $haystack, string $needle): bool
    {
        return strpos($haystack, $needle) !== false;
    }

    public static function startsWith(string $haystack, string $needle)
    {
        $length = strlen($needle);
        return (substr($haystack, 0, $length) === $needle);
    }

    public static function endsWith($haystack, $needle)
    {
        $length = strlen($needle);

        return $length === 0 ||
            (substr($haystack, -$length) === $needle);
    }

    /**
     * @param string $string
     * @param string $encoding
     * @return string
     */
    public static function uppercaseFirst(string $string, string $encoding = 'utf8'): string
    {
        $strlen = mb_strlen($string, $encoding);
        $firstChar = mb_substr($string, 0, 1, $encoding);
        $then = mb_substr($string, 1, $strlen - 1, $encoding);

        return mb_strtoupper($firstChar, $encoding) . $then;
    }

    /**
     * @param string $string
     * @return string
     */
    public static function trailingCommaToPeriod(string $string): string
    {
        if (mb_substr($string, -1) === ',') {
            $string = rtrim($string, ',') . '.';
        }

        return $string;
    }

    /**
     * @param string $string
     * @return string
     */
    public static function removeWhitespace(string $string): string
    {
        $string = str_replace(PHP_EOL, '', $string);
        $string = str_replace('\n', '', $string);
        $string = preg_replace('/\s+/', ' ', $string);

        return trim($string);
    }
}
