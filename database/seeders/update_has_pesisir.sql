-- Update region yang memiliki pesisir/laut
-- Script ini untuk update kolom has_pesisir = true untuk region yang berbatasan dengan laut

-- SUMATERA UTARA
UPDATE regions SET has_pesisir = true WHERE nama_region IN (
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
);

-- SUMATERA BARAT
UPDATE regions SET has_pesisir = true WHERE nama_region IN (
    'Kabupaten Kepulauan Mentawai',
    'Kabupaten Padang Pariaman',
    'Kabupaten Pasaman Barat',
    'Kabupaten Pesisir Selatan',
    'Kota Padang',
    'Kota Pariaman'
);

-- RIAU
UPDATE regions SET has_pesisir = true WHERE nama_region IN (
    'Kabupaten Bengkalis',
    'Kabupaten Indragiri Hilir',
    'Kabupaten Kepulauan Meranti',
    'Kabupaten Pelalawan',
    'Kabupaten Rokan Hilir',
    'Kabupaten Siak',
    'Kota Dumai'
);

-- JAMBI
UPDATE regions SET has_pesisir = true WHERE nama_region IN (
    'Kabupaten Tanjung Jabung Barat',
    'Kabupaten Tanjung Jabung Timur'
);

-- SUMATERA SELATAN
UPDATE regions SET has_pesisir = true WHERE nama_region IN (
    'Kabupaten Banyuasin',
    'Kabupaten Musi Banyuasin',
    'Kabupaten Ogan Komering Ilir'
);

-- BENGKULU
UPDATE regions SET has_pesisir = true WHERE nama_region IN (
    'Kabupaten Bengkulu Selatan',
    'Kabupaten Bengkulu Utara',
    'Kabupaten Kaur',
    'Kabupaten Mukomuko',
    'Kabupaten Seluma',
    'Kota Bengkulu'
);

-- LAMPUNG
UPDATE regions SET has_pesisir = true WHERE nama_region IN (
    'Kabupaten Lampung Selatan',
    'Kabupaten Lampung Timur',
    'Kabupaten Pesawaran',
    'Kabupaten Pesisir Barat',
    'Kabupaten Tanggamus',
    'Kota Bandar Lampung'
);

-- KEPULAUAN BANGKA BELITUNG (semua punya pesisir)
UPDATE regions SET has_pesisir = true WHERE nama_region LIKE '%Bangka%' OR nama_region LIKE '%Belitung%' OR nama_region = 'Kota Pangkal Pinang';

-- KEPULAUAN RIAU (semua punya pesisir)
UPDATE regions SET has_pesisir = true WHERE nama_region IN (
    'Kabupaten Bintan',
    'Kabupaten Karimun',
    'Kabupaten Kepulauan Anambas',
    'Kabupaten Lingga',
    'Kabupaten Natuna',
    'Kota Batam',
    'Kota Tanjung Pinang'
);

-- DKI JAKARTA (semua punya pesisir)
UPDATE regions SET has_pesisir = true WHERE nama_region IN (
    'Kota Jakarta Utara',
    'Kabupaten Kepulauan Seribu'
);

-- JAWA BARAT
UPDATE regions SET has_pesisir = true WHERE nama_region IN (
    'Kabupaten Bekasi',
    'Kabupaten Cirebon',
    'Kabupaten Indramayu',
    'Kabupaten Karawang',
    'Kabupaten Pangandaran',
    'Kabupaten Subang',
    'Kabupaten Sukabumi',
    'Kota Cirebon'
);

-- JAWA TENGAH
UPDATE regions SET has_pesisir = true WHERE nama_region IN (
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
);

-- DI YOGYAKARTA
UPDATE regions SET has_pesisir = true WHERE nama_region IN (
    'Kabupaten Bantul',
    'Kabupaten Kulon Progo'
);

-- JAWA TIMUR
UPDATE regions SET has_pesisir = true WHERE nama_region IN (
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
);

-- BANTEN
UPDATE regions SET has_pesisir = true WHERE nama_region IN (
    'Kabupaten Lebak',
    'Kabupaten Pandeglang',
    'Kabupaten Serang',
    'Kabupaten Tangerang',
    'Kota Cilegon',
    'Kota Serang',
    'Kota Tangerang'
);

-- BALI (semua punya pesisir)
UPDATE regions SET has_pesisir = true WHERE parent_id = (SELECT id FROM regions WHERE nama_region = 'Bali' AND type = 'provinsi');

-- NUSA TENGGARA BARAT (semua punya pesisir)
UPDATE regions SET has_pesisir = true WHERE parent_id = (SELECT id FROM regions WHERE nama_region = 'Nusa Tenggara Barat' AND type = 'provinsi');

-- NUSA TENGGARA TIMUR (semua punya pesisir)
UPDATE regions SET has_pesisir = true WHERE parent_id = (SELECT id FROM regions WHERE nama_region = 'Nusa Tenggara Timur' AND type = 'provinsi');

-- KALIMANTAN BARAT
UPDATE regions SET has_pesisir = true WHERE nama_region IN (
    'Kabupaten Bengkayang',
    'Kabupaten Kayong Utara',
    'Kabupaten Ketapang',
    'Kabupaten Kubu Raya',
    'Kabupaten Mempawah',
    'Kabupaten Sambas',
    'Kota Pontianak',
    'Kota Singkawang'
);

-- KALIMANTAN TENGAH
UPDATE regions SET has_pesisir = true WHERE nama_region IN (
    'Kabupaten Kotawaringin Barat',
    'Kabupaten Kotawaringin Timur',
    'Kabupaten Seruyan',
    'Kabupaten Sukamara'
);

-- KALIMANTAN SELATAN
UPDATE regions SET has_pesisir = true WHERE nama_region IN (
    'Kabupaten Banjar',
    'Kabupaten Barito Kuala',
    'Kabupaten Kotabaru',
    'Kabupaten Tanah Bumbu',
    'Kabupaten Tanah Laut',
    'Kota Banjarmasin'
);

-- KALIMANTAN TIMUR
UPDATE regions SET has_pesisir = true WHERE nama_region IN (
    'Kabupaten Berau',
    'Kabupaten Kutai Kartanegara',
    'Kabupaten Kutai Timur',
    'Kabupaten Paser',
    'Kabupaten Penajam Paser Utara',
    'Kota Balikpapan',
    'Kota Bontang',
    'Kota Samarinda'
);

-- KALIMANTAN UTARA
UPDATE regions SET has_pesisir = true WHERE nama_region IN (
    'Kabupaten Bulungan',
    'Kabupaten Nunukan',
    'Kota Tarakan'
);

-- SULAWESI UTARA (hampir semua punya pesisir)
UPDATE regions SET has_pesisir = true WHERE parent_id = (SELECT id FROM regions WHERE nama_region = 'Sulawesi Utara' AND type = 'provinsi');

-- SULAWESI TENGAH (sebagian besar punya pesisir)
UPDATE regions SET has_pesisir = true WHERE nama_region IN (
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
);

-- SULAWESI SELATAN
UPDATE regions SET has_pesisir = true WHERE nama_region IN (
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
);

-- SULAWESI TENGGARA (hampir semua punya pesisir)
UPDATE regions SET has_pesisir = true WHERE parent_id = (SELECT id FROM regions WHERE nama_region = 'Sulawesi Tenggara' AND type = 'provinsi');

-- GORONTALO
UPDATE regions SET has_pesisir = true WHERE nama_region IN (
    'Kabupaten Boalemo',
    'Kabupaten Bone Bolango',
    'Kabupaten Gorontalo',
    'Kabupaten Gorontalo Utara',
    'Kabupaten Pohuwato',
    'Kota Gorontalo'
);

-- SULAWESI BARAT (semua punya pesisir)
UPDATE regions SET has_pesisir = true WHERE parent_id = (SELECT id FROM regions WHERE nama_region = 'Sulawesi Barat' AND type = 'provinsi');

-- MALUKU (semua punya pesisir)
UPDATE regions SET has_pesisir = true WHERE parent_id = (SELECT id FROM regions WHERE nama_region = 'Maluku' AND type = 'provinsi');

-- MALUKU UTARA (semua punya pesisir)
UPDATE regions SET has_pesisir = true WHERE parent_id = (SELECT id FROM regions WHERE nama_region = 'Maluku Utara' AND type = 'provinsi');

-- PAPUA BARAT (semua punya pesisir)
UPDATE regions SET has_pesisir = true WHERE parent_id = (SELECT id FROM regions WHERE nama_region = 'Papua Barat' AND type = 'provinsi');

-- PAPUA
UPDATE regions SET has_pesisir = true WHERE nama_region IN (
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
);

-- PAPUA SELATAN (sebagian besar punya pesisir)
UPDATE regions SET has_pesisir = true WHERE nama_region IN (
    'Kabupaten Asmat',
    'Kabupaten Mappi',
    'Kabupaten Merauke'
);

-- PAPUA TENGAH
UPDATE regions SET has_pesisir = true WHERE nama_region IN (
    'Kabupaten Mimika',
    'Kabupaten Nabire'
);

-- PAPUA BARAT DAYA (semua punya pesisir)
UPDATE regions SET has_pesisir = true WHERE parent_id = (SELECT id FROM regions WHERE nama_region = 'Papua Barat Daya' AND type = 'provinsi');
