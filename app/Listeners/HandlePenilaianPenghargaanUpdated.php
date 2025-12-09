<?php

namespace App\Listeners;

use App\Events\PenilaianPenghargaanUpdated;
use App\Models\Pusdatin\Validasi1;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class HandlePenilaianPenghargaanUpdated
{
    /**
     * Create the event listener.
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     */
    public function handle(PenilaianPenghargaanUpdated $event): void
    {
        $penillaianPenghargaan = $event->penilaianPenghargaan;
        
        if ($penillaianPenghargaan->getOriginal('status') === 'finalized' && $penillaianPenghargaan->status !== 'finalized') {
            // Hapus Validasi1 (akan cascade delete Validasi2 via DB)
            Validasi1::where('penilaian_penghargaan_ref_id', $penillaianPenghargaan->id)->delete();
            
            // Hapus Wawancara untuk year terkait (tidak ada FK cascade)
            \App\Models\Pusdatin\Wawancara::where('year', $penillaianPenghargaan->year)->delete();
        }
    }  
}
