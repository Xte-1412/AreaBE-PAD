<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Region;

class RegionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // 38 Provinsi Indonesia
        $aceh = Region::create(['nama_region' => 'Aceh', 'type' => 'provinsi']);
        $sumut = Region::create(['nama_region' => 'Sumatera Utara', 'type' => 'provinsi']);
        $sumbar = Region::create(['nama_region' => 'Sumatera Barat', 'type' => 'provinsi']);
        $riau = Region::create(['nama_region' => 'Riau', 'type' => 'provinsi']);
        $jambi = Region::create(['nama_region' => 'Jambi', 'type' => 'provinsi']);
        $sumsel = Region::create(['nama_region' => 'Sumatera Selatan', 'type' => 'provinsi']);
        $bengkulu = Region::create(['nama_region' => 'Bengkulu', 'type' => 'provinsi']);
        $lampung = Region::create(['nama_region' => 'Lampung', 'type' => 'provinsi']);
        $babel = Region::create(['nama_region' => 'Kepulauan Bangka Belitung', 'type' => 'provinsi']);
        $kepri = Region::create(['nama_region' => 'Kepulauan Riau', 'type' => 'provinsi']);
        $dkijakarta = Region::create(['nama_region' => 'DKI Jakarta', 'type' => 'provinsi']);
        $jabar = Region::create(['nama_region' => 'Jawa Barat', 'type' => 'provinsi']);
        $jateng = Region::create(['nama_region' => 'Jawa Tengah', 'type' => 'provinsi']);
        $diy = Region::create(['nama_region' => 'DI Yogyakarta', 'type' => 'provinsi']);
        $jatim = Region::create(['nama_region' => 'Jawa Timur', 'type' => 'provinsi']);
        $banten = Region::create(['nama_region' => 'Banten', 'type' => 'provinsi']);
        $bali = Region::create(['nama_region' => 'Bali', 'type' => 'provinsi']);
        $ntb = Region::create(['nama_region' => 'Nusa Tenggara Barat', 'type' => 'provinsi']);
        $ntt = Region::create(['nama_region' => 'Nusa Tenggara Timur', 'type' => 'provinsi']);
        $kalbar = Region::create(['nama_region' => 'Kalimantan Barat', 'type' => 'provinsi']);
        $kalteng = Region::create(['nama_region' => 'Kalimantan Tengah', 'type' => 'provinsi']);
        $kalsel = Region::create(['nama_region' => 'Kalimantan Selatan', 'type' => 'provinsi']);
        $kaltim = Region::create(['nama_region' => 'Kalimantan Timur', 'type' => 'provinsi']);
        $kalut = Region::create(['nama_region' => 'Kalimantan Utara', 'type' => 'provinsi']);
        $sulut = Region::create(['nama_region' => 'Sulawesi Utara', 'type' => 'provinsi']);
        $sulteng = Region::create(['nama_region' => 'Sulawesi Tengah', 'type' => 'provinsi']);
        $sulsel = Region::create(['nama_region' => 'Sulawesi Selatan', 'type' => 'provinsi']);
        $sultra = Region::create(['nama_region' => 'Sulawesi Tenggara', 'type' => 'provinsi']);
        $gorontalo = Region::create(['nama_region' => 'Gorontalo', 'type' => 'provinsi']);
        $sulbar = Region::create(['nama_region' => 'Sulawesi Barat', 'type' => 'provinsi']);
        $maluku = Region::create(['nama_region' => 'Maluku', 'type' => 'provinsi']);
        $malut = Region::create(['nama_region' => 'Maluku Utara', 'type' => 'provinsi']);
        $pabar = Region::create(['nama_region' => 'Papua Barat', 'type' => 'provinsi']);
        $papua = Region::create(['nama_region' => 'Papua', 'type' => 'provinsi']);
        $papeng = Region::create(['nama_region' => 'Papua Pengunungan', 'type' => 'provinsi']);
        $papsel = Region::create(['nama_region' => 'Papua Selatan', 'type' => 'provinsi']);
        $papteng = Region::create(['nama_region' => 'Papua Tengah', 'type' => 'provinsi']);
        $papdaya = Region::create(['nama_region' => 'Papua Barat Daya', 'type' => 'provinsi']);

        // ACEH (23 kabupaten/kota)
        $this->createRegion($aceh, [
            ['Kabupaten Aceh Barat', 'kabupaten_sedang', true],
            ['Kabupaten Aceh Barat Daya', 'kabupaten_kecil', true],
            ['Kabupaten Aceh Besar', 'kabupaten_sedang', true],
            ['Kabupaten Aceh Jaya', 'kabupaten_kecil', true],
            ['Kabupaten Aceh Selatan', 'kabupaten_kecil', true],
            ['Kabupaten Aceh Singkil', 'kabupaten_kecil', true],
            ['Kabupaten Aceh Tamiang', 'kabupaten_kecil', true],
            ['Kabupaten Aceh Tengah', 'kabupaten_kecil', false],
            ['Kabupaten Aceh Tenggara', 'kabupaten_kecil', false],
            ['Kabupaten Aceh Timur', 'kabupaten_kecil', true],
            ['Kabupaten Aceh Utara', 'kabupaten_sedang', true],
            ['Kabupaten Bener Meriah', 'kabupaten_kecil', false],
            ['Kabupaten Bireuen', 'kabupaten_kecil', true],
            ['Kabupaten Gayo Lues', 'kabupaten_kecil', false],
            ['Kabupaten Nagan Raya', 'kabupaten_kecil', true],
            ['Kabupaten Pidie', 'kabupaten_sedang', true],
            ['Kabupaten Pidie Jaya', 'kabupaten_kecil', true],
            ['Kabupaten Simeulue', 'kabupaten_kecil', true],
            ['Kota Banda Aceh', 'kota_sedang', true],
            ['Kota Langsa', 'kota_kecil', false],
            ['Kota Lhokseumawe', 'kota_kecil', true],
            ['Kota Sabang', 'kota_kecil', true],
            ['Kota Subulussalam', 'kota_kecil', false],
        ]);

        // SUMATERA UTARA (33 kabupaten/kota)
        $this->createRegion($sumut, [
            ['Kabupaten Asahan', 'kabupaten_sedang', true],
            ['Kabupaten Batubara', 'kabupaten_kecil', true],
            ['Kabupaten Dairi', 'kabupaten_kecil', false],
            ['Kabupaten Deli Serdang', 'kabupaten_besar', true],
            ['Kabupaten Humbang Hasundutan', 'kabupaten_kecil', false],
            ['Kabupaten Karo', 'kabupaten_sedang', false],
            ['Kabupaten Labuhanbatu', 'kabupaten_sedang', false],
            ['Kabupaten Labuhanbatu Selatan', 'kabupaten_kecil', false],
            ['Kabupaten Labuhanbatu Utara', 'kabupaten_kecil', false],
            ['Kabupaten Langkat', 'kabupaten_besar', true],
            ['Kabupaten Mandailing Natal', 'kabupaten_sedang', true],
            ['Kabupaten Nias', 'kabupaten_kecil', true],
            ['Kabupaten Nias Barat', 'kabupaten_kecil', true],
            ['Kabupaten Nias Selatan', 'kabupaten_sedang', true],
            ['Kabupaten Nias Utara', 'kabupaten_kecil', true],
            ['Kabupaten Padang Lawas', 'kabupaten_kecil', false],
            ['Kabupaten Padang Lawas Utara', 'kabupaten_kecil', false],
            ['Kabupaten Pakpak Bharat', 'kabupaten_kecil', false],
            ['Kabupaten Samosir', 'kabupaten_kecil', false],
            ['Kabupaten Serdang Bedagai', 'kabupaten_sedang', true],
            ['Kabupaten Simalungun', 'kabupaten_besar', false],
            ['Kabupaten Tapanuli Selatan', 'kabupaten_sedang', false],
            ['Kabupaten Tapanuli Tengah', 'kabupaten_sedang', true],
            ['Kabupaten Tapanuli Utara', 'kabupaten_sedang', false],
            ['Kabupaten Toba', 'kabupaten_kecil', false],
            ['Kota Binjai', 'kota_kecil', false],
            ['Kota Gunungsitoli', 'kota_kecil', true],
            ['Kota Medan', 'kota_besar', true],
            ['Kota Padangsidimpuan', 'kota_kecil', false],
            ['Kota Pematangsiantar', 'kota_sedang', false],
            ['Kota Sibolga', 'kota_kecil', true],
            ['Kota Tanjungbalai', 'kota_kecil', true],
            ['Kota Tebing Tinggi', 'kota_kecil', false],
        ]);

        // SUMATERA BARAT (19 kabupaten/kota)
        $this->createRegion($sumbar, [
            ['Kabupaten Agam', 'kabupaten_sedang', false],
            ['Kabupaten Dharmasraya', 'kabupaten_kecil', false],
            ['Kabupaten Kepulauan Mentawai', 'kabupaten_kecil', false],
            ['Kabupaten Lima Puluh Kota', 'kabupaten_sedang', false],
            ['Kabupaten Padang Pariaman', 'kabupaten_sedang', false],
            ['Kabupaten Pasaman', 'kabupaten_sedang', false],
            ['Kabupaten Pasaman Barat', 'kabupaten_sedang', false],
            ['Kabupaten Pesisir Selatan', 'kabupaten_sedang', false],
            ['Kabupaten Sijunjung', 'kabupaten_kecil', false],
            ['Kabupaten Solok', 'kabupaten_sedang', false],
            ['Kabupaten Solok Selatan', 'kabupaten_kecil', false],
            ['Kabupaten Tanah Datar', 'kabupaten_sedang', false],
            ['Kota Bukittinggi', 'kota_kecil', false],
            ['Kota Padang', 'kota_besar', false],
            ['Kota Padang Panjang', 'kota_kecil', false],
            ['Kota Pariaman', 'kota_kecil', false],
            ['Kota Payakumbuh', 'kota_kecil', false],
            ['Kota Sawahlunto', 'kota_kecil', false],
            ['Kota Solok', 'kota_kecil', false],
        ]);

        // RIAU (12 kabupaten/kota)
        $this->createRegion($riau, [
            ['Kabupaten Bengkalis', 'kabupaten_sedang', false],
            ['Kabupaten Indragiri Hilir', 'kabupaten_sedang', false],
            ['Kabupaten Indragiri Hulu', 'kabupaten_sedang', false],
            ['Kabupaten Kampar', 'kabupaten_besar', false],
            ['Kabupaten Kepulauan Meranti', 'kabupaten_kecil', false],
            ['Kabupaten Kuantan Singingi', 'kabupaten_sedang', false],
            ['Kabupaten Pelalawan', 'kabupaten_sedang', false],
            ['Kabupaten Rokan Hilir', 'kabupaten_sedang', false],
            ['Kabupaten Rokan Hulu', 'kabupaten_sedang', false],
            ['Kabupaten Siak', 'kabupaten_sedang', false],
            ['Kota Dumai', 'kota_sedang', false],
            ['Kota Pekanbaru', 'kota_besar', false],
        ]);

        // JAMBI (11 kabupaten/kota)
        $this->createRegion($jambi, [
            ['Kabupaten Batanghari', 'kabupaten_sedang', false],
            ['Kabupaten Bungo', 'kabupaten_sedang', false],
            ['Kabupaten Kerinci', 'kabupaten_sedang', false],
            ['Kabupaten Merangin', 'kabupaten_sedang', false],
            ['Kabupaten Muaro Jambi', 'kabupaten_sedang', false],
            ['Kabupaten Sarolangun', 'kabupaten_kecil', false],
            ['Kabupaten Tanjung Jabung Barat', 'kabupaten_sedang', false],
            ['Kabupaten Tanjung Jabung Timur', 'kabupaten_kecil', false],
            ['Kabupaten Tebo', 'kabupaten_sedang', false],
            ['Kota Jambi', 'kota_sedang', false],
            ['Kota Sungai Penuh', 'kota_kecil', false],
        ]);

        // SUMATERA SELATAN (17 kabupaten/kota)
        $this->createRegion($sumsel, [
            ['Kabupaten Banyuasin', 'kabupaten_besar', false],
            ['Kabupaten Empat Lawang', 'kabupaten_kecil', false],
            ['Kabupaten Lahat', 'kabupaten_sedang', false],
            ['Kabupaten Muara Enim', 'kabupaten_sedang', false],
            ['Kabupaten Musi Banyuasin', 'kabupaten_besar', false],
            ['Kabupaten Musi Rawas', 'kabupaten_sedang', false],
            ['Kabupaten Musi Rawas Utara', 'kabupaten_kecil', false],
            ['Kabupaten Ogan Ilir', 'kabupaten_sedang', false],
            ['Kabupaten Ogan Komering Ilir', 'kabupaten_besar', false],
            ['Kabupaten Ogan Komering Ulu', 'kabupaten_sedang', false],
            ['Kabupaten Ogan Komering Ulu Selatan', 'kabupaten_kecil', false],
            ['Kabupaten Ogan Komering Ulu Timur', 'kabupaten_sedang', false],
            ['Kabupaten Penukal Abab Lematang Ilir', 'kabupaten_kecil', false],
            ['Kota Lubuklinggau', 'kota_kecil', false],
            ['Kota Pagar Alam', 'kota_kecil', false],
            ['Kota Palembang', 'kota_besar', false],
            ['Kota Prabumulih', 'kota_kecil', false],
        ]);

        // BENGKULU (10 kabupaten/kota)
        $this->createRegion($bengkulu, [
            ['Kabupaten Bengkulu Selatan', 'kabupaten_kecil', false],
            ['Kabupaten Bengkulu Tengah', 'kabupaten_kecil', false],
            ['Kabupaten Bengkulu Utara', 'kabupaten_sedang', false],
            ['Kabupaten Kaur', 'kabupaten_kecil', false],
            ['Kabupaten Kepahiang', 'kabupaten_kecil', false],
            ['Kabupaten Lebong', 'kabupaten_kecil', false],
            ['Kabupaten Mukomuko', 'kabupaten_kecil', false],
            ['Kabupaten Rejang Lebong', 'kabupaten_sedang', false],
            ['Kabupaten Seluma', 'kabupaten_kecil', false],
            ['Kota Bengkulu', 'kota_sedang', false],
        ]);

        // LAMPUNG (15 kabupaten/kota)
        $this->createRegion($lampung, [
            ['Kabupaten Lampung Barat', 'kabupaten_sedang', false],
            ['Kabupaten Lampung Selatan', 'kabupaten_besar', false],
            ['Kabupaten Lampung Tengah', 'kabupaten_besar', false],
            ['Kabupaten Lampung Timur', 'kabupaten_besar', false],
            ['Kabupaten Lampung Utara', 'kabupaten_sedang', false],
            ['Kabupaten Mesuji', 'kabupaten_kecil', false],
            ['Kabupaten Pesawaran', 'kabupaten_sedang', false],
            ['Kabupaten Pesisir Barat', 'kabupaten_kecil', false],
            ['Kabupaten Pringsewu', 'kabupaten_sedang', false],
            ['Kabupaten Tanggamus', 'kabupaten_sedang', false],
            ['Kabupaten Tulang Bawang', 'kabupaten_sedang', false],
            ['Kabupaten Tulang Bawang Barat', 'kabupaten_kecil', false],
            ['Kabupaten Way Kanan', 'kabupaten_sedang', false],
            ['Kota Bandar Lampung', 'kota_besar', false],
            ['Kota Metro', 'kota_kecil', false],
        ]);

        // KEPULAUAN BANGKA BELITUNG (7 kabupaten/kota)
        $this->createRegion($babel, [
            ['Kabupaten Bangka', 'kabupaten_sedang', false],
            ['Kabupaten Bangka Barat', 'kabupaten_kecil', false],
            ['Kabupaten Bangka Selatan', 'kabupaten_kecil', false],
            ['Kabupaten Bangka Tengah', 'kabupaten_kecil', false],
            ['Kabupaten Belitung', 'kabupaten_kecil', false],
            ['Kabupaten Belitung Timur', 'kabupaten_kecil', false],
            ['Kota Pangkal Pinang', 'kota_kecil', false],
        ]);

        // KEPULAUAN RIAU (7 kabupaten/kota)
        $this->createRegion($kepri, [
            ['Kabupaten Bintan', 'kabupaten_kecil', false],
            ['Kabupaten Karimun', 'kabupaten_sedang', false],
            ['Kabupaten Kepulauan Anambas', 'kabupaten_kecil', false],
            ['Kabupaten Lingga', 'kabupaten_kecil', false],
            ['Kabupaten Natuna', 'kabupaten_kecil', false],
            ['Kota Batam', 'kota_besar', false],
            ['Kota Tanjung Pinang', 'kota_kecil', false],
        ]);

        // DKI JAKARTA (6 kota administrasi)
        $this->createRegion($dkijakarta, [
            ['Kota Jakarta Barat', 'kota_besar', false],
            ['Kota Jakarta Pusat', 'kota_besar', false],
            ['Kota Jakarta Selatan', 'kota_besar', false],
            ['Kota Jakarta Timur', 'kota_besar', false],
            ['Kota Jakarta Utara', 'kota_besar', false],
            ['Kabupaten Kepulauan Seribu', 'kabupaten_kecil', false],
        ]);

        // JAWA BARAT (27 kabupaten/kota)
        $this->createRegion($jabar, [
            ['Kabupaten Bandung', 'kabupaten_besar', false],
            ['Kabupaten Bandung Barat', 'kabupaten_besar', false],
            ['Kabupaten Bekasi', 'kabupaten_besar', false],
            ['Kabupaten Bogor', 'kabupaten_besar', false],
            ['Kabupaten Ciamis', 'kabupaten_sedang', false],
            ['Kabupaten Cianjur', 'kabupaten_besar', false],
            ['Kabupaten Cirebon', 'kabupaten_besar', false],
            ['Kabupaten Garut', 'kabupaten_besar', false],
            ['Kabupaten Indramayu', 'kabupaten_besar', false],
            ['Kabupaten Karawang', 'kabupaten_besar', false],
            ['Kabupaten Kuningan', 'kabupaten_sedang', false],
            ['Kabupaten Majalengka', 'kabupaten_sedang', false],
            ['Kabupaten Pangandaran', 'kabupaten_sedang', false],
            ['Kabupaten Purwakarta', 'kabupaten_besar', false],
            ['Kabupaten Subang', 'kabupaten_besar', false],
            ['Kabupaten Sukabumi', 'kabupaten_besar', false],
            ['Kabupaten Sumedang', 'kabupaten_sedang', false],
            ['Kabupaten Tasikmalaya', 'kabupaten_besar', false],
            ['Kota Bandung', 'kota_besar', false],
            ['Kota Banjar', 'kota_kecil', false],
            ['Kota Bekasi', 'kota_besar', false],
            ['Kota Bogor', 'kota_besar', false],
            ['Kota Cimahi', 'kota_sedang', false],
            ['Kota Cirebon', 'kota_sedang', false],
            ['Kota Depok', 'kota_besar', false],
            ['Kota Sukabumi', 'kota_kecil', false],
            ['Kota Tasikmalaya', 'kota_sedang', false],
        ]);

        // JAWA TENGAH (35 kabupaten/kota)
        $this->createRegion($jateng, [
            ['Kabupaten Banjarnegara', 'kabupaten_sedang', false],
            ['Kabupaten Banyumas', 'kabupaten_besar', false],
            ['Kabupaten Batang', 'kabupaten_sedang', false],
            ['Kabupaten Blora', 'kabupaten_sedang', false],
            ['Kabupaten Boyolali', 'kabupaten_besar', false],
            ['Kabupaten Brebes', 'kabupaten_besar', false],
            ['Kabupaten Cilacap', 'kabupaten_besar', false],
            ['Kabupaten Demak', 'kabupaten_besar', false],
            ['Kabupaten Grobogan', 'kabupaten_besar', false],
            ['Kabupaten Jepara', 'kabupaten_sedang', false],
            ['Kabupaten Karanganyar', 'kabupaten_besar', false],
            ['Kabupaten Kebumen', 'kabupaten_sedang', false],
            ['Kabupaten Kendal', 'kabupaten_besar', false],
            ['Kabupaten Klaten', 'kabupaten_besar', false],
            ['Kabupaten Kudus', 'kabupaten_besar', false],
            ['Kabupaten Magelang', 'kabupaten_sedang', false],
            ['Kabupaten Pati', 'kabupaten_besar', false],
            ['Kabupaten Pekalongan', 'kabupaten_besar', false],
            ['Kabupaten Pemalang', 'kabupaten_besar', false],
            ['Kabupaten Purbalingga', 'kabupaten_besar', false],
            ['Kabupaten Purworejo', 'kabupaten_sedang', false],
            ['Kabupaten Rembang', 'kabupaten_sedang', false],
            ['Kabupaten Semarang', 'kabupaten_besar', false],
            ['Kabupaten Sragen', 'kabupaten_besar', false],
            ['Kabupaten Sukoharjo', 'kabupaten_besar', false],
            ['Kabupaten Tegal', 'kabupaten_besar', false],
            ['Kabupaten Temanggung', 'kabupaten_sedang', false],
            ['Kabupaten Wonogiri', 'kabupaten_besar', false],
            ['Kabupaten Wonosobo', 'kabupaten_sedang', false],
            ['Kota Magelang', 'kota_kecil', false],
            ['Kota Pekalongan', 'kota_sedang', false],
            ['Kota Salatiga', 'kota_kecil', false],
            ['Kota Semarang', 'kota_besar', false],
            ['Kota Surakarta', 'kota_sedang', false],
            ['Kota Tegal', 'kota_sedang', false],
        ]);

        // DI YOGYAKARTA (5 kabupaten/kota)
        $this->createRegion($diy, [
            ['Kabupaten Bantul', 'kabupaten_besar', false],
            ['Kabupaten Gunungkidul', 'kabupaten_sedang', false],
            ['Kabupaten Kulon Progo', 'kabupaten_sedang', false],
            ['Kabupaten Sleman', 'kabupaten_besar', false],
            ['Kota Yogyakarta', 'kota_sedang', false],
        ]);

        // JAWA TIMUR (38 kabupaten/kota)
        $this->createRegion($jatim, [
            ['Kabupaten Bangkalan', 'kabupaten_besar', false],
            ['Kabupaten Banyuwangi', 'kabupaten_besar', false],
            ['Kabupaten Blitar', 'kabupaten_besar', false],
            ['Kabupaten Bojonegoro', 'kabupaten_besar', false],
            ['Kabupaten Bondowoso', 'kabupaten_sedang', false],
            ['Kabupaten Gresik', 'kabupaten_besar', false],
            ['Kabupaten Jember', 'kabupaten_besar', false],
            ['Kabupaten Jombang', 'kabupaten_besar', false],
            ['Kabupaten Kediri', 'kabupaten_besar', false],
            ['Kabupaten Lamongan', 'kabupaten_besar', false],
            ['Kabupaten Lumajang', 'kabupaten_besar', false],
            ['Kabupaten Madiun', 'kabupaten_sedang', false],
            ['Kabupaten Magetan', 'kabupaten_sedang', false],
            ['Kabupaten Malang', 'kabupaten_besar', false],
            ['Kabupaten Mojokerto', 'kabupaten_besar', false],
            ['Kabupaten Nganjuk', 'kabupaten_besar', false],
            ['Kabupaten Ngawi', 'kabupaten_sedang', false],
            ['Kabupaten Pacitan', 'kabupaten_sedang', false],
            ['Kabupaten Pamekasan', 'kabupaten_besar', false],
            ['Kabupaten Pasuruan', 'kabupaten_besar', false],
            ['Kabupaten Ponorogo', 'kabupaten_besar', false],
            ['Kabupaten Probolinggo', 'kabupaten_besar', false],
            ['Kabupaten Sampang', 'kabupaten_besar', false],
            ['Kabupaten Sidoarjo', 'kabupaten_besar', false],
            ['Kabupaten Situbondo', 'kabupaten_sedang', false],
            ['Kabupaten Sumenep', 'kabupaten_besar', false],
            ['Kabupaten Trenggalek', 'kabupaten_sedang', false],
            ['Kabupaten Tuban', 'kabupaten_besar', false],
            ['Kabupaten Tulungagung', 'kabupaten_besar', false],
            ['Kota Batu', 'kota_kecil', false],
            ['Kota Blitar', 'kota_kecil', false],
            ['Kota Kediri', 'kota_sedang', false],
            ['Kota Madiun', 'kota_kecil', false],
            ['Kota Malang', 'kota_besar', false],
            ['Kota Mojokerto', 'kota_kecil', false],
            ['Kota Pasuruan', 'kota_kecil', false],
            ['Kota Probolinggo', 'kota_kecil', false],
            ['Kota Surabaya', 'kota_besar', false],
        ]);

        // BANTEN (8 kabupaten/kota)
        $this->createRegion($banten, [
            ['Kabupaten Lebak', 'kabupaten_sedang', false],
            ['Kabupaten Pandeglang', 'kabupaten_sedang', false],
            ['Kabupaten Serang', 'kabupaten_besar', false],
            ['Kabupaten Tangerang', 'kabupaten_besar', false],
            ['Kota Cilegon', 'kota_sedang', false],
            ['Kota Serang', 'kota_sedang', false],
            ['Kota Tangerang', 'kota_besar', false],
            ['Kota Tangerang Selatan', 'kota_besar', false],
        ]);

        // BALI (9 kabupaten/kota)
        $this->createRegion($bali, [
            ['Kabupaten Badung', 'kabupaten_sedang', false],
            ['Kabupaten Bangli', 'kabupaten_kecil', false],
            ['Kabupaten Buleleng', 'kabupaten_sedang', false],
            ['Kabupaten Gianyar', 'kabupaten_sedang', false],
            ['Kabupaten Jembrana', 'kabupaten_kecil', false],
            ['Kabupaten Karangasem', 'kabupaten_sedang', false],
            ['Kabupaten Klungkung', 'kabupaten_kecil', false],
            ['Kabupaten Tabanan', 'kabupaten_sedang', false],
            ['Kota Denpasar', 'kota_besar', false],
        ]);

        // NUSA TENGGARA BARAT (10 kabupaten/kota)
        $this->createRegion($ntb, [
            ['Kabupaten Bima', 'kabupaten_sedang', false],
            ['Kabupaten Dompu', 'kabupaten_kecil', false],
            ['Kabupaten Lombok Barat', 'kabupaten_sedang', false],
            ['Kabupaten Lombok Tengah', 'kabupaten_besar', false],
            ['Kabupaten Lombok Timur', 'kabupaten_besar', false],
            ['Kabupaten Lombok Utara', 'kabupaten_kecil', false],
            ['Kabupaten Sumbawa', 'kabupaten_sedang', false],
            ['Kabupaten Sumbawa Barat', 'kabupaten_kecil', false],
            ['Kota Bima', 'kota_kecil', false],
            ['Kota Mataram', 'kota_sedang', false],
        ]);

        // NUSA TENGGARA TIMUR (22 kabupaten/kota)
        $this->createRegion($ntt, [
            ['Kabupaten Alor', 'kabupaten_kecil', false],
            ['Kabupaten Belu', 'kabupaten_kecil', false],
            ['Kabupaten Ende', 'kabupaten_sedang', false],
            ['Kabupaten Flores Timur', 'kabupaten_kecil', false],
            ['Kabupaten Kupang', 'kabupaten_sedang', false],
            ['Kabupaten Lembata', 'kabupaten_kecil', false],
            ['Kabupaten Malaka', 'kabupaten_kecil', false],
            ['Kabupaten Manggarai', 'kabupaten_sedang', false],
            ['Kabupaten Manggarai Barat', 'kabupaten_kecil', false],
            ['Kabupaten Manggarai Timur', 'kabupaten_kecil', false],
            ['Kabupaten Nagekeo', 'kabupaten_kecil', false],
            ['Kabupaten Ngada', 'kabupaten_kecil', false],
            ['Kabupaten Rote Ndao', 'kabupaten_kecil', false],
            ['Kabupaten Sabu Raijua', 'kabupaten_kecil', false],
            ['Kabupaten Sikka', 'kabupaten_sedang', false],
            ['Kabupaten Sumba Barat', 'kabupaten_kecil', false],
            ['Kabupaten Sumba Barat Daya', 'kabupaten_kecil', false],
            ['Kabupaten Sumba Tengah', 'kabupaten_kecil', false],
            ['Kabupaten Sumba Timur', 'kabupaten_kecil', false],
            ['Kabupaten Timor Tengah Selatan', 'kabupaten_sedang', false],
            ['Kabupaten Timor Tengah Utara', 'kabupaten_kecil', false],
            ['Kota Kupang', 'kota_sedang', false],
        ]);

        // KALIMANTAN BARAT (14 kabupaten/kota)
        $this->createRegion($kalbar, [
            ['Kabupaten Bengkayang', 'kabupaten_kecil', false],
            ['Kabupaten Kapuas Hulu', 'kabupaten_kecil', false],
            ['Kabupaten Kayong Utara', 'kabupaten_kecil', false],
            ['Kabupaten Ketapang', 'kabupaten_sedang', false],
            ['Kabupaten Kubu Raya', 'kabupaten_sedang', false],
            ['Kabupaten Landak', 'kabupaten_sedang', false],
            ['Kabupaten Melawi', 'kabupaten_kecil', false],
            ['Kabupaten Mempawah', 'kabupaten_kecil', false],
            ['Kabupaten Sambas', 'kabupaten_sedang', false],
            ['Kabupaten Sanggau', 'kabupaten_sedang', false],
            ['Kabupaten Sekadau', 'kabupaten_kecil', false],
            ['Kabupaten Sintang', 'kabupaten_sedang', false],
            ['Kota Pontianak', 'kota_sedang', false],
            ['Kota Singkawang', 'kota_kecil', false],
        ]);

        // KALIMANTAN TENGAH (14 kabupaten/kota)
        $this->createRegion($kalteng, [
            ['Kabupaten Barito Selatan', 'kabupaten_kecil', false],
            ['Kabupaten Barito Timur', 'kabupaten_kecil', false],
            ['Kabupaten Barito Utara', 'kabupaten_kecil', false],
            ['Kabupaten Gunung Mas', 'kabupaten_kecil', false],
            ['Kabupaten Kapuas', 'kabupaten_sedang', false],
            ['Kabupaten Katingan', 'kabupaten_kecil', false],
            ['Kabupaten Kotawaringin Barat', 'kabupaten_sedang', false],
            ['Kabupaten Kotawaringin Timur', 'kabupaten_sedang', false],
            ['Kabupaten Lamandau', 'kabupaten_kecil', false],
            ['Kabupaten Murung Raya', 'kabupaten_kecil', false],
            ['Kabupaten Pulang Pisau', 'kabupaten_kecil', false],
            ['Kabupaten Seruyan', 'kabupaten_kecil', false],
            ['Kabupaten Sukamara', 'kabupaten_kecil', false],
            ['Kota Palangka Raya', 'kota_sedang', false],
        ]);

        // KALIMANTAN SELATAN (13 kabupaten/kota)
        $this->createRegion($kalsel, [
            ['Kabupaten Balangan', 'kabupaten_kecil', false],
            ['Kabupaten Banjar', 'kabupaten_sedang', false],
            ['Kabupaten Barito Kuala', 'kabupaten_sedang', false],
            ['Kabupaten Hulu Sungai Selatan', 'kabupaten_kecil', false],
            ['Kabupaten Hulu Sungai Tengah', 'kabupaten_kecil', false],
            ['Kabupaten Hulu Sungai Utara', 'kabupaten_kecil', false],
            ['Kabupaten Kotabaru', 'kabupaten_sedang', false],
            ['Kabupaten Tabalong', 'kabupaten_kecil', false],
            ['Kabupaten Tanah Bumbu', 'kabupaten_kecil', false],
            ['Kabupaten Tanah Laut', 'kabupaten_sedang', false],
            ['Kabupaten Tapin', 'kabupaten_kecil', false],
            ['Kota Banjarbaru', 'kota_kecil', false],
            ['Kota Banjarmasin', 'kota_sedang', false],
        ]);

        // KALIMANTAN TIMUR (10 kabupaten/kota)
        $this->createRegion($kaltim, [
            ['Kabupaten Berau', 'kabupaten_kecil', false],
            ['Kabupaten Kutai Barat', 'kabupaten_kecil', false],
            ['Kabupaten Kutai Kartanegara', 'kabupaten_sedang', false],
            ['Kabupaten Kutai Timur', 'kabupaten_sedang', false],
            ['Kabupaten Mahakam Ulu', 'kabupaten_kecil', false],
            ['Kabupaten Paser', 'kabupaten_sedang', false],
            ['Kabupaten Penajam Paser Utara', 'kabupaten_kecil', false],
            ['Kota Balikpapan', 'kota_sedang', false],
            ['Kota Bontang', 'kota_kecil', false],
            ['Kota Samarinda', 'kota_besar', false],
        ]);

        // KALIMANTAN UTARA (5 kabupaten/kota)
        $this->createRegion($kalut, [
            ['Kabupaten Bulungan', 'kabupaten_kecil', false],
            ['Kabupaten Malinau', 'kabupaten_kecil', false],
            ['Kabupaten Nunukan', 'kabupaten_kecil', false],
            ['Kabupaten Tana Tidung', 'kabupaten_kecil', false],
            ['Kota Tarakan', 'kota_kecil', false],
        ]);

        // SULAWESI UTARA (15 kabupaten/kota)
        $this->createRegion($sulut, [
            ['Kabupaten Bolaang Mongondow', 'kabupaten_kecil', false],
            ['Kabupaten Bolaang Mongondow Selatan', 'kabupaten_kecil', false],
            ['Kabupaten Bolaang Mongondow Timur', 'kabupaten_kecil', false],
            ['Kabupaten Bolaang Mongondow Utara', 'kabupaten_kecil', false],
            ['Kabupaten Kepulauan Sangihe', 'kabupaten_kecil', false],
            ['Kabupaten Kepulauan Siau Tagulandang Biaro', 'kabupaten_kecil', false],
            ['Kabupaten Kepulauan Talaud', 'kabupaten_kecil', false],
            ['Kabupaten Minahasa', 'kabupaten_sedang', false],
            ['Kabupaten Minahasa Selatan', 'kabupaten_sedang', false],
            ['Kabupaten Minahasa Tenggara', 'kabupaten_kecil', false],
            ['Kabupaten Minahasa Utara', 'kabupaten_kecil', false],
            ['Kota Bitung', 'kota_kecil', false],
            ['Kota Kotamobagu', 'kota_kecil', false],
            ['Kota Manado', 'kota_sedang', false],
            ['Kota Tomohon', 'kota_kecil', false],
        ]);

        // SULAWESI TENGAH (13 kabupaten/kota)
        $this->createRegion($sulteng, [
            ['Kabupaten Banggai', 'kabupaten_sedang', false],
            ['Kabupaten Banggai Kepulauan', 'kabupaten_kecil', false],
            ['Kabupaten Banggai Laut', 'kabupaten_kecil', false],
            ['Kabupaten Buol', 'kabupaten_kecil', false],
            ['Kabupaten Donggala', 'kabupaten_sedang', false],
            ['Kabupaten Morowali', 'kabupaten_kecil', false],
            ['Kabupaten Morowali Utara', 'kabupaten_kecil', false],
            ['Kabupaten Parigi Moutong', 'kabupaten_sedang', false],
            ['Kabupaten Poso', 'kabupaten_kecil', false],
            ['Kabupaten Sigi', 'kabupaten_sedang', false],
            ['Kabupaten Tojo Una-Una', 'kabupaten_kecil', false],
            ['Kabupaten Toli-Toli', 'kabupaten_kecil', false],
            ['Kota Palu', 'kota_sedang', false],
        ]);

        // SULAWESI SELATAN (24 kabupaten/kota)
        $this->createRegion($sulsel, [
            ['Kabupaten Bantaeng', 'kabupaten_kecil', false],
            ['Kabupaten Barru', 'kabupaten_kecil', false],
            ['Kabupaten Bone', 'kabupaten_sedang', false],
            ['Kabupaten Bulukumba', 'kabupaten_sedang', false],
            ['Kabupaten Enrekang', 'kabupaten_kecil', false],
            ['Kabupaten Gowa', 'kabupaten_sedang', false],
            ['Kabupaten Jeneponto', 'kabupaten_sedang', false],
            ['Kabupaten Kepulauan Selayar', 'kabupaten_kecil', false],
            ['Kabupaten Luwu', 'kabupaten_sedang', false],
            ['Kabupaten Luwu Timur', 'kabupaten_sedang', false],
            ['Kabupaten Luwu Utara', 'kabupaten_sedang', false],
            ['Kabupaten Maros', 'kabupaten_sedang', false],
            ['Kabupaten Pangkajene dan Kepulauan', 'kabupaten_sedang', false],
            ['Kabupaten Pinrang', 'kabupaten_sedang', false],
            ['Kabupaten Sidenreng Rappang', 'kabupaten_sedang', false],
            ['Kabupaten Sinjai', 'kabupaten_kecil', false],
            ['Kabupaten Soppeng', 'kabupaten_kecil', false],
            ['Kabupaten Takalar', 'kabupaten_sedang', false],
            ['Kabupaten Tana Toraja', 'kabupaten_kecil', false],
            ['Kabupaten Toraja Utara', 'kabupaten_kecil', false],
            ['Kabupaten Wajo', 'kabupaten_sedang', false],
            ['Kota Makassar', 'kota_besar', false],
            ['Kota Palopo', 'kota_kecil', false],
            ['Kota Parepare', 'kota_kecil', false],
        ]);

        // SULAWESI TENGGARA (17 kabupaten/kota)
        $this->createRegion($sultra, [
            ['Kabupaten Bombana', 'kabupaten_kecil', false],
            ['Kabupaten Buton', 'kabupaten_kecil', false],
            ['Kabupaten Buton Selatan', 'kabupaten_kecil', false],
            ['Kabupaten Buton Tengah', 'kabupaten_kecil', false],
            ['Kabupaten Buton Utara', 'kabupaten_kecil', false],
            ['Kabupaten Kolaka', 'kabupaten_kecil', false],
            ['Kabupaten Kolaka Timur', 'kabupaten_kecil', false],
            ['Kabupaten Kolaka Utara', 'kabupaten_kecil', false],
            ['Kabupaten Konawe', 'kabupaten_sedang', false],
            ['Kabupaten Konawe Kepulauan', 'kabupaten_kecil', false],
            ['Kabupaten Konawe Selatan', 'kabupaten_sedang', false],
            ['Kabupaten Konawe Utara', 'kabupaten_kecil', false],
            ['Kabupaten Muna', 'kabupaten_sedang', false],
            ['Kabupaten Muna Barat', 'kabupaten_kecil', false],
            ['Kabupaten Wakatobi', 'kabupaten_kecil', false],
            ['Kota Bau-Bau', 'kota_kecil', false],
            ['Kota Kendari', 'kota_sedang', false],
        ]);

        // GORONTALO (6 kabupaten/kota)
        $this->createRegion($gorontalo, [
            ['Kabupaten Boalemo', 'kabupaten_kecil', false],
            ['Kabupaten Bone Bolango', 'kabupaten_kecil', false],
            ['Kabupaten Gorontalo', 'kabupaten_sedang', false],
            ['Kabupaten Gorontalo Utara', 'kabupaten_kecil', false],
            ['Kabupaten Pohuwato', 'kabupaten_kecil', false],
            ['Kota Gorontalo', 'kota_kecil', false],
        ]);

        // SULAWESI BARAT (6 kabupaten)
        $this->createRegion($sulbar, [
            ['Kabupaten Majene', 'kabupaten_kecil', false],
            ['Kabupaten Mamasa', 'kabupaten_kecil', false],
            ['Kabupaten Mamuju', 'kabupaten_sedang', false],
            ['Kabupaten Mamuju Tengah', 'kabupaten_kecil', false],
            ['Kabupaten Mamuju Utara', 'kabupaten_kecil', false],
            ['Kabupaten Polewali Mandar', 'kabupaten_sedang', false],
        ]);

        // MALUKU (11 kabupaten/kota)
        $this->createRegion($maluku, [
            ['Kabupaten Buru', 'kabupaten_kecil', false],
            ['Kabupaten Buru Selatan', 'kabupaten_kecil', false],
            ['Kabupaten Kepulauan Aru', 'kabupaten_kecil', false],
            ['Kabupaten Maluku Barat Daya', 'kabupaten_kecil', false],
            ['Kabupaten Maluku Tengah', 'kabupaten_sedang', false],
            ['Kabupaten Maluku Tenggara', 'kabupaten_kecil', false],
            ['Kabupaten Maluku Tenggara Barat', 'kabupaten_kecil', false],
            ['Kabupaten Seram Bagian Barat', 'kabupaten_kecil', false],
            ['Kabupaten Seram Bagian Timur', 'kabupaten_kecil', false],
            ['Kota Ambon', 'kota_sedang', false],
            ['Kota Tual', 'kota_kecil', false],
        ]);

        // MALUKU UTARA (10 kabupaten/kota)
        $this->createRegion($malut, [
            ['Kabupaten Halmahera Barat', 'kabupaten_kecil', false],
            ['Kabupaten Halmahera Selatan', 'kabupaten_kecil', false],
            ['Kabupaten Halmahera Tengah', 'kabupaten_kecil', false],
            ['Kabupaten Halmahera Timur', 'kabupaten_kecil', false],
            ['Kabupaten Halmahera Utara', 'kabupaten_kecil', false],
            ['Kabupaten Kepulauan Sula', 'kabupaten_kecil', false],
            ['Kabupaten Pulau Morotai', 'kabupaten_kecil', false],
            ['Kabupaten Pulau Taliabu', 'kabupaten_kecil', false],
            ['Kota Ternate', 'kota_kecil', false],
            ['Kota Tidore Kepulauan', 'kota_kecil', false],
        ]);

        // PAPUA BARAT (13 kabupaten/kota)
        $this->createRegion($pabar, [
            ['Kabupaten Fakfak', 'kabupaten_kecil', false],
            ['Kabupaten Kaimana', 'kabupaten_kecil', false],
            ['Kabupaten Manokwari', 'kabupaten_sedang', false],
            ['Kabupaten Manokwari Selatan', 'kabupaten_kecil', false],
            ['Kabupaten Maybrat', 'kabupaten_kecil', false],
            ['Kabupaten Pegunungan Arfak', 'kabupaten_kecil', false],
            ['Kabupaten Raja Ampat', 'kabupaten_kecil', false],
            ['Kabupaten Sorong', 'kabupaten_sedang', false],
            ['Kabupaten Sorong Selatan', 'kabupaten_kecil', false],
            ['Kabupaten Tambrauw', 'kabupaten_kecil', false],
            ['Kabupaten Teluk Bintuni', 'kabupaten_kecil', false],
            ['Kabupaten Teluk Wondama', 'kabupaten_kecil', false],
            ['Kota Sorong', 'kota_kecil', false],
        ]);

        // PAPUA (28 kabupaten/kota)
        $this->createRegion($papua, [
            ['Kabupaten Asmat', 'kabupaten_kecil', false],
            ['Kabupaten Biak Numfor', 'kabupaten_kecil', false],
            ['Kabupaten Boven Digoel', 'kabupaten_kecil', false],
            ['Kabupaten Deiyai', 'kabupaten_kecil', false],
            ['Kabupaten Dogiyai', 'kabupaten_kecil', false],
            ['Kabupaten Intan Jaya', 'kabupaten_kecil', false],
            ['Kabupaten Jayapura', 'kabupaten_sedang', false],
            ['Kabupaten Jayawijaya', 'kabupaten_kecil', false],
            ['Kabupaten Keerom', 'kabupaten_kecil', false],
            ['Kabupaten Kepulauan Yapen', 'kabupaten_kecil', false],
            ['Kabupaten Lanny Jaya', 'kabupaten_kecil', false],
            ['Kabupaten Mamberamo Raya', 'kabupaten_kecil', false],
            ['Kabupaten Mamberamo Tengah', 'kabupaten_kecil', false],
            ['Kabupaten Mappi', 'kabupaten_kecil', false],
            ['Kabupaten Merauke', 'kabupaten_kecil', false],
            ['Kabupaten Mimika', 'kabupaten_kecil', false],
            ['Kabupaten Nabire', 'kabupaten_kecil', false],
            ['Kabupaten Nduga', 'kabupaten_kecil', false],
            ['Kabupaten Paniai', 'kabupaten_kecil', false],
            ['Kabupaten Pegunungan Bintang', 'kabupaten_kecil', false],
            ['Kabupaten Puncak', 'kabupaten_kecil', false],
            ['Kabupaten Puncak Jaya', 'kabupaten_kecil', false],
            ['Kabupaten Sarmi', 'kabupaten_kecil', false],
            ['Kabupaten Supiori', 'kabupaten_kecil', false],
            ['Kabupaten Tolikara', 'kabupaten_kecil', false],
            ['Kabupaten Waropen', 'kabupaten_kecil', false],
            ['Kabupaten Yahukimo', 'kabupaten_kecil', false],
            ['Kota Jayapura', 'kota_sedang', false],
        ]);

        // PAPUA PENGUNUNGAN (8 kabupaten)
        $this->createRegion($papeng, [
            ['Kabupaten Jayawijaya', 'kabupaten_kecil', false],
            ['Kabupaten Lanny Jaya', 'kabupaten_kecil', false],
            ['Kabupaten Mamberamo Tengah', 'kabupaten_kecil', false],
            ['Kabupaten Nduga', 'kabupaten_kecil', false],
            ['Kabupaten Pegunungan Bintang', 'kabupaten_kecil', false],
            ['Kabupaten Tolikara', 'kabupaten_kecil', false],
            ['Kabupaten Yahukimo', 'kabupaten_kecil', false],
            ['Kabupaten Yalimo', 'kabupaten_kecil', false],
        ]);

        // PAPUA SELATAN (4 kabupaten)
        $this->createRegion($papsel, [
            ['Kabupaten Asmat', 'kabupaten_kecil', false],
            ['Kabupaten Boven Digoel', 'kabupaten_kecil', false],
            ['Kabupaten Mappi', 'kabupaten_kecil', false],
            ['Kabupaten Merauke', 'kabupaten_kecil', false],
        ]);

        // PAPUA TENGAH (8 kabupaten)
        $this->createRegion($papteng, [
            ['Kabupaten Deiyai', 'kabupaten_kecil', false],
            ['Kabupaten Dogiyai', 'kabupaten_kecil', false],
            ['Kabupaten Intan Jaya', 'kabupaten_kecil', false],
            ['Kabupaten Mimika', 'kabupaten_kecil', false],
            ['Kabupaten Nabire', 'kabupaten_kecil', false],
            ['Kabupaten Paniai', 'kabupaten_kecil', false],
            ['Kabupaten Puncak', 'kabupaten_kecil', false],
            ['Kabupaten Puncak Jaya', 'kabupaten_kecil', false],
        ]);

        // PAPUA BARAT DAYA (5 kabupaten)
        $this->createRegion($papdaya, [
            ['Kabupaten Fakfak', 'kabupaten_kecil', false],
            ['Kabupaten Kaimana', 'kabupaten_kecil', false],
            ['Kabupaten Maybrat', 'kabupaten_kecil', false],
            ['Kabupaten Raja Ampat', 'kabupaten_kecil', false],
            ['Kabupaten Sorong Selatan', 'kabupaten_kecil', false],
        ]);
    }

    private function createRegion($parent, array $regions)
    {
        foreach ($regions as $region) {
            Region::create([
                'nama_region' => $region[0],
                'type' => 'kabupaten/kota',
                'parent_id' => $parent->id,
                'kategori' => $region[1],
                'has_pesisir' => $region[2] ?? false, // Default false jika tidak disebutkan
            ]);
        }
    }
}

