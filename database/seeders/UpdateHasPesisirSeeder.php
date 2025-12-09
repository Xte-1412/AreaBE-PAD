<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class UpdateHasPesisirSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // SUMATERA UTARA
        DB::table('regions')->whereIn('nama_region', [
            'Kabupaten Asahan',
            'Kabupaten Batubara',
            'Kabupaten Deli Serdang',
            'Kabupaten Langkat',
            'Kabupaten Mandailing Natal',
            'Kabupaten Nias',
            'Kabupaten Nias Barat',
            'Kabupaten Nias Selatan',
            'Kabupaten Nias Utara',
            'Kabupaten Serdang Bedagai',
            'Kabupaten Tapanuli Tengah',
            'Kota Gunungsitoli',
            'Kota Medan',
            'Kota Sibolga',
            'Kota Tanjungbalai'
        ])->update(['has_pesisir' => true]);

        // SUMATERA BARAT
        DB::table('regions')->whereIn('nama_region', [
            'Kabupaten Kepulauan Mentawai',
            'Kabupaten Padang Pariaman',
            'Kabupaten Pasaman Barat',
            'Kabupaten Pesisir Selatan',
            'Kota Padang',
            'Kota Pariaman'
        ])->update(['has_pesisir' => true]);

        // RIAU
        DB::table('regions')->whereIn('nama_region', [
            'Kabupaten Bengkalis',
            'Kabupaten Indragiri Hilir',
            'Kabupaten Kepulauan Meranti',
            'Kabupaten Pelalawan',
            'Kabupaten Rokan Hilir',
            'Kabupaten Siak',
            'Kota Dumai'
        ])->update(['has_pesisir' => true]);

        // JAMBI
        DB::table('regions')->whereIn('nama_region', [
            'Kabupaten Tanjung Jabung Barat',
            'Kabupaten Tanjung Jabung Timur'
        ])->update(['has_pesisir' => true]);

        // SUMATERA SELATAN
        DB::table('regions')->whereIn('nama_region', [
            'Kabupaten Banyuasin',
            'Kabupaten Musi Banyuasin',
            'Kabupaten Ogan Komering Ilir'
        ])->update(['has_pesisir' => true]);

        // BENGKULU
        DB::table('regions')->whereIn('nama_region', [
            'Kabupaten Bengkulu Selatan',
            'Kabupaten Bengkulu Utara',
            'Kabupaten Kaur',
            'Kabupaten Mukomuko',
            'Kabupaten Seluma',
            'Kota Bengkulu'
        ])->update(['has_pesisir' => true]);

        // LAMPUNG
        DB::table('regions')->whereIn('nama_region', [
            'Kabupaten Lampung Selatan',
            'Kabupaten Lampung Timur',
            'Kabupaten Pesawaran',
            'Kabupaten Pesisir Barat',
            'Kabupaten Tanggamus',
            'Kota Bandar Lampung'
        ])->update(['has_pesisir' => true]);

        // KEPULAUAN BANGKA BELITUNG (semua punya pesisir)
        DB::table('regions')
            ->where(function($query) {
                $query->where('nama_region', 'LIKE', '%Bangka%')
                      ->orWhere('nama_region', 'LIKE', '%Belitung%')
                      ->orWhere('nama_region', '=', 'Kota Pangkal Pinang');
            })
            ->update(['has_pesisir' => true]);

        // KEPULAUAN RIAU (semua punya pesisir)
        DB::table('regions')->whereIn('nama_region', [
            'Kabupaten Bintan',
            'Kabupaten Karimun',
            'Kabupaten Kepulauan Anambas',
            'Kabupaten Lingga',
            'Kabupaten Natuna',
            'Kota Batam',
            'Kota Tanjung Pinang'
        ])->update(['has_pesisir' => true]);

        // DKI JAKARTA
        DB::table('regions')->whereIn('nama_region', [
            'Kota Jakarta Utara',
            'Kabupaten Kepulauan Seribu'
        ])->update(['has_pesisir' => true]);

        // JAWA BARAT
        DB::table('regions')->whereIn('nama_region', [
            'Kabupaten Bekasi',
            'Kabupaten Cirebon',
            'Kabupaten Indramayu',
            'Kabupaten Karawang',
            'Kabupaten Pangandaran',
            'Kabupaten Subang',
            'Kabupaten Sukabumi',
            'Kota Cirebon'
        ])->update(['has_pesisir' => true]);

        // JAWA TENGAH
        DB::table('regions')->whereIn('nama_region', [
            'Kabupaten Batang',
            'Kabupaten Brebes',
            'Kabupaten Cilacap',
            'Kabupaten Demak',
            'Kabupaten Jepara',
            'Kabupaten Kebumen',
            'Kabupaten Kendal',
            'Kabupaten Pati',
            'Kabupaten Pekalongan',
            'Kabupaten Pemalang',
            'Kabupaten Purworejo',
            'Kabupaten Rembang',
            'Kabupaten Semarang',
            'Kabupaten Tegal',
            'Kota Pekalongan',
            'Kota Semarang',
            'Kota Tegal'
        ])->update(['has_pesisir' => true]);

        // DI YOGYAKARTA
        DB::table('regions')->whereIn('nama_region', [
            'Kabupaten Bantul',
            'Kabupaten Kulon Progo'
        ])->update(['has_pesisir' => true]);

        // JAWA TIMUR
        DB::table('regions')->whereIn('nama_region', [
            'Kabupaten Bangkalan',
            'Kabupaten Banyuwangi',
            'Kabupaten Gresik',
            'Kabupaten Jember',
            'Kabupaten Lamongan',
            'Kabupaten Lumajang',
            'Kabupaten Malang',
            'Kabupaten Pamekasan',
            'Kabupaten Pasuruan',
            'Kabupaten Probolinggo',
            'Kabupaten Sampang',
            'Kabupaten Sidoarjo',
            'Kabupaten Situbondo',
            'Kabupaten Sumenep',
            'Kabupaten Tuban',
            'Kota Pasuruan',
            'Kota Probolinggo',
            'Kota Surabaya'
        ])->update(['has_pesisir' => true]);

        // BANTEN
        DB::table('regions')->whereIn('nama_region', [
            'Kabupaten Lebak',
            'Kabupaten Pandeglang',
            'Kabupaten Serang',
            'Kabupaten Tangerang',
            'Kota Cilegon',
            'Kota Serang',
            'Kota Tangerang'
        ])->update(['has_pesisir' => true]);

        // BALI (semua punya pesisir)
        $baliId = DB::table('regions')->where('nama_region', 'Bali')->where('type', 'provinsi')->first()->id;
        DB::table('regions')->where('parent_id', $baliId)->update(['has_pesisir' => true]);

        // NUSA TENGGARA BARAT (semua punya pesisir)
        $ntbId = DB::table('regions')->where('nama_region', 'Nusa Tenggara Barat')->where('type', 'provinsi')->first()->id;
        DB::table('regions')->where('parent_id', $ntbId)->update(['has_pesisir' => true]);

        // NUSA TENGGARA TIMUR (semua punya pesisir)
        $nttId = DB::table('regions')->where('nama_region', 'Nusa Tenggara Timur')->where('type', 'provinsi')->first()->id;
        DB::table('regions')->where('parent_id', $nttId)->update(['has_pesisir' => true]);

        // KALIMANTAN BARAT
        DB::table('regions')->whereIn('nama_region', [
            'Kabupaten Bengkayang',
            'Kabupaten Kayong Utara',
            'Kabupaten Ketapang',
            'Kabupaten Kubu Raya',
            'Kabupaten Mempawah',
            'Kabupaten Sambas',
            'Kota Pontianak',
            'Kota Singkawang'
        ])->update(['has_pesisir' => true]);

        // KALIMANTAN TENGAH
        DB::table('regions')->whereIn('nama_region', [
            'Kabupaten Kotawaringin Barat',
            'Kabupaten Kotawaringin Timur',
            'Kabupaten Seruyan',
            'Kabupaten Sukamara'
        ])->update(['has_pesisir' => true]);

        // KALIMANTAN SELATAN
        DB::table('regions')->whereIn('nama_region', [
            'Kabupaten Banjar',
            'Kabupaten Barito Kuala',
            'Kabupaten Kotabaru',
            'Kabupaten Tanah Bumbu',
            'Kabupaten Tanah Laut',
            'Kota Banjarmasin'
        ])->update(['has_pesisir' => true]);

        // KALIMANTAN TIMUR
        DB::table('regions')->whereIn('nama_region', [
            'Kabupaten Berau',
            'Kabupaten Kutai Kartanegara',
            'Kabupaten Kutai Timur',
            'Kabupaten Paser',
            'Kabupaten Penajam Paser Utara',
            'Kota Balikpapan',
            'Kota Bontang',
            'Kota Samarinda'
        ])->update(['has_pesisir' => true]);

        // KALIMANTAN UTARA
        DB::table('regions')->whereIn('nama_region', [
            'Kabupaten Bulungan',
            'Kabupaten Nunukan',
            'Kota Tarakan'
        ])->update(['has_pesisir' => true]);

        // SULAWESI UTARA (hampir semua punya pesisir)
        $sulutId = DB::table('regions')->where('nama_region', 'Sulawesi Utara')->where('type', 'provinsi')->first()->id;
        DB::table('regions')->where('parent_id', $sulutId)->update(['has_pesisir' => true]);

        // SULAWESI TENGAH
        DB::table('regions')->whereIn('nama_region', [
            'Kabupaten Banggai',
            'Kabupaten Banggai Kepulauan',
            'Kabupaten Banggai Laut',
            'Kabupaten Buol',
            'Kabupaten Donggala',
            'Kabupaten Morowali',
            'Kabupaten Morowali Utara',
            'Kabupaten Parigi Moutong',
            'Kabupaten Poso',
            'Kabupaten Tojo Una-Una',
            'Kabupaten Toli-Toli',
            'Kota Palu'
        ])->update(['has_pesisir' => true]);

        // SULAWESI SELATAN
        DB::table('regions')->whereIn('nama_region', [
            'Kabupaten Bantaeng',
            'Kabupaten Barru',
            'Kabupaten Bone',
            'Kabupaten Bulukumba',
            'Kabupaten Jeneponto',
            'Kabupaten Kepulauan Selayar',
            'Kabupaten Luwu',
            'Kabupaten Luwu Timur',
            'Kabupaten Luwu Utara',
            'Kabupaten Maros',
            'Kabupaten Pangkajene dan Kepulauan',
            'Kabupaten Pinrang',
            'Kabupaten Sinjai',
            'Kabupaten Takalar',
            'Kabupaten Wajo',
            'Kota Makassar',
            'Kota Parepare'
        ])->update(['has_pesisir' => true]);

        // SULAWESI TENGGARA (hampir semua punya pesisir)
        $sultraId = DB::table('regions')->where('nama_region', 'Sulawesi Tenggara')->where('type', 'provinsi')->first()->id;
        DB::table('regions')->where('parent_id', $sultraId)->update(['has_pesisir' => true]);

        // GORONTALO
        DB::table('regions')->whereIn('nama_region', [
            'Kabupaten Boalemo',
            'Kabupaten Bone Bolango',
            'Kabupaten Gorontalo',
            'Kabupaten Gorontalo Utara',
            'Kabupaten Pohuwato',
            'Kota Gorontalo'
        ])->update(['has_pesisir' => true]);

        // SULAWESI BARAT (semua punya pesisir)
        $sulbarId = DB::table('regions')->where('nama_region', 'Sulawesi Barat')->where('type', 'provinsi')->first()->id;
        DB::table('regions')->where('parent_id', $sulbarId)->update(['has_pesisir' => true]);

        // MALUKU (semua punya pesisir)
        $malukuId = DB::table('regions')->where('nama_region', 'Maluku')->where('type', 'provinsi')->first()->id;
        DB::table('regions')->where('parent_id', $malukuId)->update(['has_pesisir' => true]);

        // MALUKU UTARA (semua punya pesisir)
        $malutId = DB::table('regions')->where('nama_region', 'Maluku Utara')->where('type', 'provinsi')->first()->id;
        DB::table('regions')->where('parent_id', $malutId)->update(['has_pesisir' => true]);

        // PAPUA BARAT (semua punya pesisir)
        $pabarId = DB::table('regions')->where('nama_region', 'Papua Barat')->where('type', 'provinsi')->first()->id;
        DB::table('regions')->where('parent_id', $pabarId)->update(['has_pesisir' => true]);

        // PAPUA
        DB::table('regions')->whereIn('nama_region', [
            'Kabupaten Asmat',
            'Kabupaten Biak Numfor',
            'Kabupaten Boven Digoel',
            'Kabupaten Jayapura',
            'Kabupaten Kepulauan Yapen',
            'Kabupaten Mamberamo Raya',
            'Kabupaten Mappi',
            'Kabupaten Merauke',
            'Kabupaten Mimika',
            'Kabupaten Nabire',
            'Kabupaten Sarmi',
            'Kabupaten Supiori',
            'Kabupaten Waropen',
            'Kota Jayapura'
        ])->update(['has_pesisir' => true]);

        // PAPUA SELATAN
        DB::table('regions')->whereIn('nama_region', [
            'Kabupaten Asmat',
            'Kabupaten Mappi',
            'Kabupaten Merauke'
        ])->update(['has_pesisir' => true]);

        // PAPUA TENGAH
        DB::table('regions')->whereIn('nama_region', [
            'Kabupaten Mimika',
            'Kabupaten Nabire'
        ])->update(['has_pesisir' => true]);

        // PAPUA BARAT DAYA (semua punya pesisir)
        $papdayaId = DB::table('regions')->where('nama_region', 'Papua Barat Daya')->where('type', 'provinsi')->first()->id;
        DB::table('regions')->where('parent_id', $papdayaId)->update(['has_pesisir' => true]);

        echo "✓ Update has_pesisir berhasil!\n";
    }
}
