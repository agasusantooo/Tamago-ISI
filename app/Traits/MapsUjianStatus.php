<?php

namespace App\Traits;

use Illuminate\Support\Facades\DB;

trait MapsUjianStatus
{
    /**
     * Map application status values to valid DB enum values for status_ujian.
     * Caches the allowed enum values to avoid repeated DB queries.
     */
    private static $ujianStatusCache = null;

    /**
     * Get allowed enum values for status_ujian from DB.
     */
    public function getUjianStatusEnumValues()
    {
        if (self::$ujianStatusCache !== null) {
            return self::$ujianStatusCache;
        }

        try {
            $col = DB::select("SHOW COLUMNS FROM ujian_tugas_akhir LIKE 'status_ujian'");
            if (!empty($col)) {
                $type = $col[0]->Type ?? null;
                if ($type && preg_match_all("/'([^']+)'/", $type, $m)) {
                    self::$ujianStatusCache = $m[1];
                    return self::$ujianStatusCache;
                }
            }
        } catch (\Throwable $e) {
            // ignore
        }

        return [];
    }

    /**
     * Map an application status value to a valid DB enum value.
     * Uses fuzzy matching (case-insensitive, underscores to spaces).
     */
    public function mapUjianStatus($appValue)
    {
        $allowed = $this->getUjianStatusEnumValues();
        if (empty($allowed)) {
            // fallback: return as-is if we can't read enum
            return $appValue;
        }

        // exact match (case-insensitive)
        foreach ($allowed as $dbVal) {
            if (strtolower($dbVal) === strtolower($appValue)) {
                return $dbVal;
            }
        }

        // replace underscores with spaces and try again
        $normalized = str_replace('_', ' ', $appValue);
        foreach ($allowed as $dbVal) {
            if (strtolower($dbVal) === strtolower($normalized)) {
                return $dbVal;
            }
        }

        // replace underscores with empty and try again (e.g., belum_ujian -> belumujian)
        $normalized = str_replace('_', '', $appValue);
        foreach ($allowed as $dbVal) {
            if (strtolower(str_replace('_', '', $dbVal)) === strtolower($normalized)) {
                return $dbVal;
            }
        }

        // keyword matching: if app value contains a word from allowed, use it
        $appWords = explode('_', $appValue);
        foreach ($appWords as $word) {
            foreach ($allowed as $dbVal) {
                if (stripos($dbVal, $word) !== false || stripos($word, $dbVal) !== false) {
                    return $dbVal;
                }
            }
        }

        // fallback: use first allowed value to guarantee DB insert succeeds
        return $allowed[0];
    }
}
