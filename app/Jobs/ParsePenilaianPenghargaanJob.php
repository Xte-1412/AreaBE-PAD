<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;
use App\Models\Pusdatin\PenilaianPenghargaan;
use Illuminate\Support\Facades\Storage;
use App\Models\Pusdatin\Parsed\PenilaianPenghargaan_Parsed;
use Throwable;
use Spatie\SimpleExcel\SimpleExcelReader;

class ParsePenilaianPenghargaanJob implements ShouldQueue
{
    use Queueable;
    protected $batch;

    /**
     * Create a new job instance.
     */
    public function __construct($batch)
    {
        $this->batch = $batch;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
         $this->batch->update(['status' => 'parsing']);
        $bobot=[
            'Adipura' => 0.35,
            'Adiwiyata' => 0.15,
            'Proklim' => 0.19,
            'Proper' => 0.21,
            'Kalpataru' => 0.1,
        ];
        $map = [
            'id_dinas' => 'int',
            'nama_dinas' => 'string',
            'Adipura_Jumlah_Wilayah'=> 'int',
            'Adipura_Skor_Max'=> 'int',
            'Adipura_Skor'=> 'int',
            'Adiwiyata_Jumlah_Sekolah'=> 'int',
            'Adiwiyata_Skor_Max'=> 'int',
            'Adiwiyata_Skor'=> 'int',
            'Proklim_Jumlah_Desa'=> 'int',
            'Proklim_Skor_Max'=> 'int',
            'Proklim_Skor'=> 'int',
            'Proper_Jumlah_Perusahaan'=> 'int',
            'Proper_Skor_Max'=> 'int',
            'Proper_Skor'=> 'int',
            'Kalpataru_Jumlah_Penerima'=> 'int',
            'Kalpataru_Skor_Max'=> 'int',
            'Kalpataru_Skor'=> 'int',
            // Sesuaikan dengan struktur file Excel penghargaan Anda
        ];
        $rowToInsert=[];
        try{
            $filepath= Storage::disk('pusdatin')->path($this->batch->file_path);
             Log::info("Parsing penilaian penghargaan file: " . $filepath);

            // Eager load semua dinas sekali untuk performance (1 query saja)
            $allDinas = \App\Models\Dinas::all()->keyBy('id');

            SimpleExcelReader::create($filepath)
                ->noHeaderRow()
                ->skip(2)
                ->getRows()
                ->each(function(array $rowValues) use ($map, &$rowToInsert, $bobot, $allDinas) {
                    $errors = [];
                    
                    $data = [
                        'penilaian_penghargaan_id' => $this->batch->id,
                    ];
                    $index=0;
                    foreach ($map as $field => $type) {
                        // Skip nama_dinas dari Excel, akan diambil dari database
                        if ($field === 'nama_dinas') {
                            $index++; // Skip index untuk nama_dinas di Excel
                            continue;
                        }

                        $data[$field] = safe(
                            $field, 
                            fn() => validateValue($rowValues[$index++] ?? null, $type), 
                            $errors
                        );
                    }
                    
                    $data['Adipura_Persentase'] = safe(
                        'Adipura_Persentase', 
                        fn() => $data['Adipura_Skor_Max'] > 0 ? ($data['Adipura_Skor'] / $data['Adipura_Skor_Max']) * 100 : 0, 
                        $errors
                    );
                    $data['Adiwiyata_Persentase'] = safe(
                        'Adiwiyata_Persentase', 
                        fn() => $data['Adiwiyata_Skor_Max'] > 0 ? ($data['Adiwiyata_Skor'] / $data['Adiwiyata_Skor_Max']) * 100 : 0, 
                        $errors
                    );
                    $data['Proklim_Persentase'] = safe(
                        'Proklim_Persentase',  
                        fn() => $data['Proklim_Skor_Max'] > 0 ? ($data['Proklim_Skor'] / $data['Proklim_Skor_Max']) * 100 : 0, 
                        $errors
                    );
                    $data['Proper_Persentase'] = safe(
                        'Proper_Persentase',  
                        fn() => $data['Proper_Skor_Max'] > 0 ? ($data['Proper_Skor'] / $data['Proper_Skor_Max']) * 100 : 0, 
                        $errors
                    );  
                    $data['Kalpataru_Persentase'] = safe(
                        'Kalpataru_Persentase',  
                        fn() => $data['Kalpataru_Skor_Max'] > 0 ? ($data['Kalpataru_Skor'] / $data['Kalpataru_Skor_Max']) * 100 : 0, 
                        $errors
                    );
                    $data['Total_Skor'] = safe(
                        'Total_Skor',  
                        fn() => ($data['Adipura_Skor']*$bobot['Adipura']) + ($data['Adiwiyata_Skor']*$bobot['Adiwiyata']) + ($data['Proklim_Skor']*$bobot['Proklim']) + ($data['Proper_Skor']*$bobot['Proper']) + ($data['Kalpataru_Skor']*$bobot['Kalpataru']), 
                        $errors
                    );

                    // Validasi dan ambil nama dinas dari database (lebih konsisten)
                    if (isset($data['id_dinas']) && $data['id_dinas'] !== null) {
                        $dinas = $allDinas->get($data['id_dinas']);
                        if ($dinas) {
                            $data['nama_dinas'] = $dinas->nama_dinas;
                        } else {
                            $errors['id_dinas'] = "Dinas dengan ID {$data['id_dinas']} belum terdaftar di sistem.";
                            $data['nama_dinas'] = $rowValues[1] ?? null; // Fallback ke Excel (index 1 untuk nama_dinas)
                        }
                    } else {
                        $data['nama_dinas'] = $rowValues[1] ?? null; // Fallback jika id_dinas null
                    }

                    $data['status'] = empty($errors) ? 'parsed_ok' : 'parsed_error';
                    $data['error_messages'] = empty($errors) ? null : json_encode($errors);
                    $data['created_at'] = now();
                    $data['updated_at'] = now();
                    // PenilaianPenghargaan_Parsed::create($data);
                    $rowToInsert[] = $data;

                });
            if(!empty($rowToInsert)){
                PenilaianPenghargaan_Parsed::insert($rowToInsert);
            }
            
            $this->batch->update(['status' => 'parsed_ok']);
            
            Log::info("Parsing penilaian penghargaan completed successfully");

        }catch(Throwable $e){
           Log::error("Fatal parsing error penilaian penghargaan: " . $e->getMessage(), [
                'file' => $e->getFile(),
                'line' => $e->getLine(),
            ]);

            $this->batch->update([
                'status' => 'parsed_failed',
                'error_messages' => $e->getMessage()
            ]);
        }

    }
}
