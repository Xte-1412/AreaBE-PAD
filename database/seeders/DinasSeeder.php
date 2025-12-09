<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Dinas;
use App\Models\Region;

class DinasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Mapping provinsi codes
        $provinsiCodes = [
            'Aceh' => '11',
            'Sumatera Utara' => '12',
            'Sumatera Barat' => '13',
            'Riau' => '14',
            'Jambi' => '15',
            'Sumatera Selatan' => '16',
            'Bengkulu' => '17',
            'Lampung' => '18',
            'Kepulauan Bangka Belitung' => '19',
            'Kepulauan Riau' => '21',
            'DKI Jakarta' => '31',
            'Jawa Barat' => '32',
            'Jawa Tengah' => '33',
            'DI Yogyakarta' => '34',
            'Jawa Timur' => '35',
            'Banten' => '36',
            'Bali' => '51',
            'Nusa Tenggara Barat' => '52',
            'Nusa Tenggara Timur' => '53',
            'Kalimantan Barat' => '61',
            'Kalimantan Tengah' => '62',
            'Kalimantan Selatan' => '63',
            'Kalimantan Timur' => '64',
            'Kalimantan Utara' => '65',
            'Sulawesi Utara' => '71',
            'Sulawesi Tengah' => '72',
            'Sulawesi Selatan' => '73',
            'Sulawesi Tenggara' => '74',
            'Gorontalo' => '75',
            'Sulawesi Barat' => '76',
            'Maluku' => '81',
            'Maluku Utara' => '82',
            'Papua Barat' => '91',
            'Papua' => '94',
            'Papua Pengunungan' => '95',
            'Papua Selatan' => '96',
            'Papua Tengah' => '97',
            'Papua Barat Daya' => '92',
        ];

        $regions = Region::with('parent')->get();

        foreach ($regions as $reg) {
            $namadinas = 'Dinas Lingkungan Hidup';
            
            if ($reg->type == 'provinsi') {
                $namadinas .= ' Provinsi ' . $reg->nama_region;
            } else {
                $namadinas .= ' ' . $reg->nama_region;
            }
            
            // Generate kode_dinas: format DLH-{8 random alphanumeric uppercase}
            // Contoh: DLH-A7K9X2M5, DLH-3P8R6W1Q
            $kodeDinas = 'DLH-' . strtoupper(substr(bin2hex(random_bytes(4)), 0, 8));
            
            Dinas::create([
                'region_id' => $reg->id,
                'nama_dinas' => $namadinas,
                'kode_dinas' => $kodeDinas,
                'status' => 'belum_terdaftar',
            ]);
        }
    }
}