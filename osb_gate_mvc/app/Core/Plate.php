<?php
declare(strict_types=1);

namespace App\Core;

final class Plate
{
    public static function toIntl(string $input): string
    {
        $raw = self::asciiUpper($input);
        $raw = \preg_replace('/[^A-Z0-9]/', '', $raw) ?? '';

        if (\preg_match('/^(0[1-9]|[1-7][0-9]|8[01])([A-Z]{1,3})(\d{2,4})$/', $raw)) {
            return 'TR-' . $raw;
        }
        return $raw;
    }

    public static function toTrDisplay(string $intl): string
    {
        $s = self::asciiUpper($intl);
        $s = \str_replace(['-', ' '], '', $s);

        // <<< ÖNEMLİ: global fonksiyon olarak çağır
        if (\function_exists('str_starts_with') ? \str_starts_with($s, 'TR') : (0 === \strncmp($s, 'TR', 2))) {
            $s = \ltrim(\substr($s, 2), '-');
        }

        if (\preg_match('/^(0[1-9]|[1-7][0-9]|8[01])([A-Z]{1,3})(\d{2,4})$/', $s, $m)) {
            return $m[1] . ' ' . $m[2] . ' ' . $m[3];
        }
        return $intl;
    }

    private static function asciiUpper(string $text): string
    {
        $map = [
            'ı' => 'i','İ' => 'I','ş' => 's','Ş' => 'S',
            'ğ' => 'g','Ğ' => 'G','ü' => 'u','Ü' => 'U',
            'ö' => 'o','Ö' => 'O','ç' => 'c','Ç' => 'C',
        ];
        $text = \strtr($text, $map);
        if (\function_exists('transliterator_transliterate')) {
            $text = \transliterator_transliterate('Any-Latin; Latin-ASCII', $text);
        }
        return \mb_strtoupper($text, 'UTF-8');
    }
}
