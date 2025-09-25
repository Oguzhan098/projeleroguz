<?php
declare(strict_types=1);

namespace App\Core;

final class Time
{
    /** Uygulama zaman dilimi (varsayılan Europe/Istanbul) */
    public static function tz(): \DateTimeZone
    {
        $tz = $_ENV['APP_TZ'] ?? 'Europe/Istanbul';
        return new \DateTimeZone($tz);
    }

    /** UTC timestamp'ı (Y-m-d H:i:s) yerel (TR) string'e çevirir. Boşsa '' döner. */
    public static function fmtLocal(?string $utcTs, string $format = 'Y-m-d H:i'): string
    {
        if (!$utcTs) return '';
        try {
            $dt = new \DateTime($utcTs, new \DateTimeZone('UTC'));
            $dt->setTimezone(self::tz());
            return $dt->format($format);
        } catch (\Throwable $e) {
            return (string)$utcTs;
        }
    }

    /** Bugünün yerel tarihi (TR) – Y-m-d */
    public static function todayLocal(): string
    {
        $now = new \DateTime('now', self::tz());
        return $now->format('Y-m-d');
    }

    /**
     * Yerel (TR) tarih + saat/dk → UTC 'Y-m-d H:i:s'
     * - $dateLocal boşsa bugünün yerel tarihi alınır
     * - $hour veya $min null ise null döner
     */
    public static function localDateHmToUtc(?int $hour, ?int $min, ?string $dateLocal = null): ?string
    {
        if ($hour === null || $min === null) return null;
        $dateLocal = $dateLocal ?: self::todayLocal();

        $h = str_pad((string)max(0, min(23, $hour)), 2, '0', STR_PAD_LEFT);
        $m = str_pad((string)max(0, min(59, $min )), 2, '0', STR_PAD_LEFT);

        $dt = new \DateTime($dateLocal . ' ' . $h . ':' . $m . ':00', self::tz());
        $dt->setTimezone(new \DateTimeZone('UTC'));
        return $dt->format('Y-m-d H:i:s');
    }

    /**
     * Yerel (TR) gün başlangıcı/sonunu UTC'ye çevirir.
     * - $dateLocal: 'Y-m-d'
     * - $endOfDay: false => 00:00:00, true => 23:59:59
     */
    public static function localDayEdgeToUtc(string $dateLocal, bool $endOfDay = false): string
    {
        $time = $endOfDay ? '23:59:59' : '00:00:00';
        $dt = new \DateTime($dateLocal . ' ' . $time, self::tz());
        $dt->setTimezone(new \DateTimeZone('UTC'));
        return $dt->format('Y-m-d H:i:s');
    }
}
