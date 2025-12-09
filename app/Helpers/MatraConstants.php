<?php

namespace App\Helpers;

class MatraConstants
{
    /**
     * Mapping kode tabel ke matra
     * 78 Tabel Utama SLHD
     */
    const TABEL_TO_MATRA = [
        // 2.1 Keanekaragaman Hayati (Tabel 9-12)
        'Tabel 9' => 'Keanekaragaman Hayati',
        'Tabel 10' => 'Keanekaragaman Hayati',
        'Tabel 11' => 'Keanekaragaman Hayati',
        'Tabel 12' => 'Keanekaragaman Hayati',

        // 2.2 Kualitas Air (Tabel 13-21)
        'Tabel 13' => 'Kualitas Air',
        'Tabel 14' => 'Kualitas Air',
        'Tabel 15' => 'Kualitas Air',
        'Tabel 16' => 'Kualitas Air',
        'Tabel 17' => 'Kualitas Air',
        'Tabel 18' => 'Kualitas Air',
        'Tabel 19' => 'Kualitas Air',
        'Tabel 20' => 'Kualitas Air',
        'Tabel 21' => 'Kualitas Air',

        // 2.3 Laut, Pesisir, dan Pantai (Tabel 22-29)
        'Tabel 22' => 'Laut, Pesisir, dan Pantai',
        'Tabel 23' => 'Laut, Pesisir, dan Pantai',
        'Tabel 24' => 'Laut, Pesisir, dan Pantai',
        'Tabel 25' => 'Laut, Pesisir, dan Pantai',
        'Tabel 26' => 'Laut, Pesisir, dan Pantai',
        'Tabel 27' => 'Laut, Pesisir, dan Pantai',
        'Tabel 28' => 'Laut, Pesisir, dan Pantai',
        'Tabel 29' => 'Laut, Pesisir, dan Pantai',

        // 2.4 Kualitas Udara (Tabel 30-35)
        'Tabel 30' => 'Kualitas Udara',
        'Tabel 31' => 'Kualitas Udara',
        'Tabel 32' => 'Kualitas Udara',
        'Tabel 33' => 'Kualitas Udara',
        'Tabel 34' => 'Kualitas Udara',
        'Tabel 35' => 'Kualitas Udara',

        // 2.5 Lahan dan Hutan (Tabel 36-49)
        'Tabel 36' => 'Lahan dan Hutan',
        'Tabel 37' => 'Lahan dan Hutan',
        'Tabel 38' => 'Lahan dan Hutan',
        'Tabel 39' => 'Lahan dan Hutan',
        'Tabel 40' => 'Lahan dan Hutan',
        'Tabel 41' => 'Lahan dan Hutan',
        'Tabel 42' => 'Lahan dan Hutan',
        'Tabel 43' => 'Lahan dan Hutan',
        'Tabel 44' => 'Lahan dan Hutan',
        'Tabel 45' => 'Lahan dan Hutan',
        'Tabel 46' => 'Lahan dan Hutan',
        'Tabel 47' => 'Lahan dan Hutan',
        'Tabel 48' => 'Lahan dan Hutan',
        'Tabel 49' => 'Lahan dan Hutan',

        // 2.6 Pengelolaan Sampah dan Limbah (Tabel 50-54)
        'Tabel 50' => 'Pengelolaan Sampah dan Limbah',
        'Tabel 51' => 'Pengelolaan Sampah dan Limbah',
        'Tabel 52' => 'Pengelolaan Sampah dan Limbah',
        'Tabel 53' => 'Pengelolaan Sampah dan Limbah',
        'Tabel 54' => 'Pengelolaan Sampah dan Limbah',

        // 2.7 Perubahan Iklim (Tabel 55-57)
        'Tabel 55' => 'Perubahan Iklim',
        'Tabel 56' => 'Perubahan Iklim',
        'Tabel 57' => 'Perubahan Iklim',

        // 2.8 Risiko Bencana (Tabel 58-62)
        'Tabel 58' => 'Risiko Bencana',
        'Tabel 59' => 'Risiko Bencana',
        'Tabel 60' => 'Risiko Bencana',
        'Tabel 61' => 'Risiko Bencana',
        'Tabel 62' => 'Risiko Bencana',

        // Lainnya (Tabel 1-8 dan Tabel 63-78)
        'Tabel 1' => 'Lainnya',
        'Tabel 2' => 'Lainnya',
        'Tabel 3' => 'Lainnya',
        'Tabel 4' => 'Lainnya',
        'Tabel 5' => 'Lainnya',
        'Tabel 6' => 'Lainnya',
        'Tabel 7' => 'Lainnya',
        'Tabel 8' => 'Lainnya',
        'Tabel 63' => 'Lainnya',
        'Tabel 64' => 'Lainnya',
        'Tabel 65' => 'Lainnya',
        'Tabel 66' => 'Lainnya',
        'Tabel 67' => 'Lainnya',
        'Tabel 68' => 'Lainnya',
        'Tabel 69' => 'Lainnya',
        'Tabel 70' => 'Lainnya',
        'Tabel 71' => 'Lainnya',
        'Tabel 72' => 'Lainnya',
        'Tabel 73' => 'Lainnya',
        'Tabel 74' => 'Lainnya',
        'Tabel 75' => 'Lainnya',
        'Tabel 76' => 'Lainnya',
        'Tabel 77' => 'Lainnya',
        'Tabel 78' => 'Lainnya',
    ];

    /**
     * 8 Kategori Matra Utama
     */
    const MATRA_LIST = [
        'Keanekaragaman Hayati',
        'Kualitas Air',
        'Laut, Pesisir, dan Pantai',
        'Kualitas Udara',
        'Lahan dan Hutan',
        'Pengelolaan Sampah dan Limbah',
        'Perubahan Iklim',
        'Risiko Bencana',
        'Lainnya',
    ];

    /**
     * Get matra by kode tabel
     */
    public static function getMatraByKode(string $kodeTabel): ?string
    {
        return self::TABEL_TO_MATRA[$kodeTabel] ?? 'Lainnya';
    }

    /**
     * Check if kode tabel valid
     */
    public static function isValidKode(string $kodeTabel): bool
    {
        return isset(self::TABEL_TO_MATRA[$kodeTabel]);
    }

    /**
     * Get all valid kode tabel
     */
    public static function getAllKodeTabel(): array
    {
        return array_keys(self::TABEL_TO_MATRA);
    }
}
