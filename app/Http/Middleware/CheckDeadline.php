<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use App\Models\Deadline;
use Carbon\Carbon;

class CheckDeadline
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     * @param  string  $stage  Stage name: submission, penilaian_slhd, penilaian_penghargaan, validasi_1, validasi_2
     */
    public function handle(Request $request, Closure $next, string $stage): Response
    {
        // Ambil tahun dari route parameter atau submission
        $year = $request->route('year') ?? $request->submission?->tahun ?? now()->year;

        // Cek apakah ada deadline aktif untuk stage ini
        $deadline = Deadline::byYear($year)
            ->byStage($stage)
            ->active()
            ->first();

        if ($deadline && $deadline->isPassed()) {
            return response()->json([
                'message' => "Deadline untuk {$stage} tahun {$year} sudah terlewati pada " . $deadline->deadline_at->format('d-m-Y H:i'),
                'deadline' => $deadline->deadline_at->format('d-m-Y H:i:s')
            ], 403);
        }

        return $next($request);
    }
}
