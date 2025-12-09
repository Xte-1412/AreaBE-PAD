<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Submission;
use App\Models\Region;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class DashboardController extends Controller
{
    /**
     * Get dashboard statistics
     */
    public function getStats(Request $request)
    {
        $year = $request->input('year', now()->year);
        
        // User statistics
        $totalUsers = User::count();
        $pendingApproval = User::where('is_active', false)->count();
        $activeUsers = User::where('is_active', true)->count();
        
        // Users by role
        $usersByRole = User::select('role', DB::raw('count(*) as count'))
            ->groupBy('role')
            ->get()
            ->pluck('count', 'role');
        
        // Users by region type (untuk DLH)
        $usersByRegionType = User::where('role', 'dinas')
            ->join('dinas', 'users.dinas_id', '=', 'dinas.id')
            ->join('regions', 'dinas.region_id', '=', 'regions.id')
            ->select('regions.type', DB::raw('count(*) as count'))
            ->groupBy('regions.type')
            ->get()
            ->pluck('count', 'type');
        
        // Submission statistics
        $totalSubmissions = Submission::where('tahun', $year)->count();
        $submissionsByStatus = Submission::where('tahun', $year)
            ->select('status', DB::raw('count(*) as count'))
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');
        
        // Storage usage (dalam MB)
        $storageUsed = 0;
        $storagePath = storage_path('app/dlh');
        if (file_exists($storagePath)) {
            $storageUsed = $this->getDirSize($storagePath) / (1024 * 1024); // Convert to MB
        }
        
        return response()->json([
            // Format untuk FE (flat structure)
            'total_users_aktif' => $activeUsers,
            'total_users_pending' => $pendingApproval,
            
            // Detail tambahan (optional, FE bisa pakai kalau perlu)
            'year' => $year,
            'users' => [
                'total' => $totalUsers,
                'pending_approval' => $pendingApproval,
                'active' => $activeUsers,
                'by_role' => [
                    'admin' => $usersByRole['admin'] ?? 0,
                    'pusdatin' => $usersByRole['pusdatin'] ?? 0,
                    'dinas' => $usersByRole['dinas'] ?? 0,
                ],
                'dinas_by_type' => [
                    'provinsi' => $usersByRegionType['provinsi'] ?? 0,
                    'kabupaten_kota' => $usersByRegionType['kabupaten/kota'] ?? 0,
                ],
            ],
            'submissions' => [
                'total' => $totalSubmissions,
                'by_status' => [
                    'draft' => $submissionsByStatus['draft'] ?? 0,
                    'finalized' => $submissionsByStatus['finalized'] ?? 0,
                    'approved' => $submissionsByStatus['approved'] ?? 0,
                ],
            ],
            'storage' => [
                'used_mb' => round($storageUsed, 2),
                'used_gb' => round($storageUsed / 1024, 2),
            ],
        ]);
    }
    
    /**
     * Get recent activities
     */
    public function getRecentActivities(Request $request)
    {
        $limit = $request->input('limit', 10);
        
        // Get recent user registrations
        $recentUsers = User::with(['dinas.region'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($user) {
                return [
                    'type' => 'user_registration',
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'user_role' => $user->role,
                    'dinas_name' => $user->dinas?->nama_dinas,
                    'status' => $user->is_active ? 'approved' : 'pending',
                    'timestamp' => $user->created_at,
                ];
            });
        
        // Get recent submissions
        $recentSubmissions = Submission::with(['dinas'])
            ->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get()
            ->map(function ($submission) {
                return [
                    'type' => 'submission',
                    'submission_id' => $submission->id,
                    'dinas_name' => $submission->dinas?->nama_dinas,
                    'year' => $submission->tahun,
                    'status' => $submission->status,
                    'timestamp' => $submission->created_at,
                ];
            });
        
        // Merge and sort by timestamp
        $activities = $recentUsers->concat($recentSubmissions)
            ->sortByDesc('timestamp')
            ->take($limit)
            ->values();
        
        return response()->json([
            'activities' => $activities,
            'total' => $activities->count(),
        ]);
    }
    
    /**
     * Get user detail
     */
    public function getUserDetail($id)
    {
        $user = User::with([
            'dinas.region.parent',
            'submissions' => function ($query) {
                $query->orderBy('tahun', 'desc');
            },
        ])->findOrFail($id);
        
        $region = $user->dinas?->region;
        $provinsi = null;
        $kabupatenKota = null;
        
        if ($region) {
            if ($region->type === 'provinsi') {
                $provinsi = $region->nama_region;
            } else {
                $kabupatenKota = $region->nama_region;
                $provinsi = $region->parent?->nama_region;
            }
        }
        
        return response()->json([
            'user' => [
                'id' => $user->id,
                'email' => $user->email,
                'role' => [
                    'name' => $user->role,
                ],
                'is_active' => $user->is_active,
                'created_at' => $user->created_at,
                'updated_at' => $user->updated_at,
            ],
            'dinas' => $user->dinas ? [
                'id' => $user->dinas->id,
                'nama' => $user->dinas->nama_dinas,
                'kode' => $user->dinas->kode_dinas,
                'provinsi' => $provinsi,
                'kabupaten_kota' => $kabupatenKota,
                'type' => $region?->type,
                'kategori' => $region?->kategori,
            ] : null,
            'submissions' => $user->submissions->map(function ($submission) {
                return [
                    'id' => $submission->id,
                    'tahun' => $submission->tahun,
                    'status' => $submission->status,
                    'created_at' => $submission->created_at,
                    'updated_at' => $submission->updated_at,
                ];
            }),
            'submissions_count' => $user->submissions->count(),
        ]);
    }
    
    /**
     * Calculate directory size recursively
     */
    private function getDirSize($dir)
    {
        $size = 0;
        foreach (glob(rtrim($dir, '/').'/*', GLOB_NOSORT) as $each) {
            $size += is_file($each) ? filesize($each) : $this->getDirSize($each);
        }
        return $size;
    }
}
