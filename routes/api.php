<?php

use App\Http\Controllers\Api\SiswaController;
use App\Http\Controllers\Api\GuruController;
use App\Http\Controllers\Api\KelasController;
use App\Http\Controllers\Api\NilaiController;
use App\Http\Controllers\Api\AbsensiController;
use App\Http\Controllers\Api\AuthController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Auth;

// Public health check
Route::get('/health', function () {
    return response()->json([
        'status' => 'OK',
        'timestamp' => now(),
        'service' => 'SIAKAD API'
    ]);
});

// API key validation
Route::get('/validate', [AuthController::class, 'validateKey']);

// Protected API routes
Route::middleware(['api.auth', 'api.rate_limit'])->group(function () {
    
    // Siswa endpoints
    Route::get('/siswa', [SiswaController::class, 'index'])
        ->middleware('api.permission:siswa,read');
    Route::get('/siswa/{id}', [SiswaController::class, 'show'])
        ->middleware('api.permission:siswa,read');
    
    // Guru endpoints
    Route::get('/guru', [GuruController::class, 'index'])
        ->middleware('api.permission:guru,read');
    Route::get('/guru/{id}', [GuruController::class, 'show'])
        ->middleware('api.permission:guru,read');
    
    // Kelas endpoints
    Route::get('/kelas', [KelasController::class, 'index'])
        ->middleware('api.permission:kelas,read');
    Route::get('/kelas/{id}', [KelasController::class, 'show'])
        ->middleware('api.permission:kelas,read');
    
    // Nilai endpoints
    Route::get('/nilai', [NilaiController::class, 'index'])
        ->middleware('api.permission:nilai,read');
    Route::post('/nilai', [NilaiController::class, 'store'])
        ->middleware('api.permission:nilai,write');
    
    // Absensi endpoints
    Route::get('/absensi', [AbsensiController::class, 'index'])
        ->middleware('api.permission:absensi,read');
    Route::post('/absensi', [AbsensiController::class, 'store'])
        ->middleware('api.permission:absensi,write');
});

// ==================== NOTIFICATION ROUTES ====================
// Routes untuk Alpine.js Notification Panel (gunakan web middleware)
Route::middleware(['web', 'auth'])->group(function () {
    
    // Get notifications untuk user yang login
    Route::get('/notifications', function (Request $request) {
        try {
            $user = Auth::user();
            
            if (!$user) {
                return response()->json([
                    'notifications' => [],
                    'unread_count' => 0
                ]);
            }

            $notifications = $user->notifications()
                ->orderBy('created_at', 'desc')
                ->take(10)
                ->get()
                ->map(function($notification) {
                    $data = json_decode($notification->data, true);
                    return [
                        'id' => $notification->id,
                        'title' => $data['title'] ?? 'Notifikasi',
                        'message' => $data['message'] ?? '',
                        'type' => $data['type'] ?? 'info',
                        'data' => $data['data'] ?? [],
                        'read_at' => $notification->read_at,
                        'created_at' => $notification->created_at->diffForHumans(),
                        'is_read' => !is_null($notification->read_at)
                    ];
                });

            $unreadCount = $user->unreadNotifications()->count();

            return response()->json([
                'success' => true,
                'notifications' => $notifications,
                'unread_count' => $unreadCount
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to load notifications',
                'notifications' => [],
                'unread_count' => 0
            ], 500);
        }
    });

    // Mark single notification as read
    Route::post('/notifications/{id}/read', function (Request $request, $id) {
        try {
            $user = Auth::user();
            $notification = $user->notifications()->find($id);
            
            if ($notification) {
                $notification->update(['read_at' => now()]);
                
                // Get updated count
                $unreadCount = $user->unreadNotifications()->count();
                
                return response()->json([
                    'success' => true,
                    'message' => 'Notification marked as read',
                    'unread_count' => $unreadCount
                ]);
            }

            return response()->json([
                'success' => false,
                'error' => 'Notification not found'
            ], 404);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to mark notification as read'
            ], 500);
        }
    });

    // Mark all notifications as read
    Route::post('/notifications/mark-all-read', function (Request $request) {
        try {
            $user = Auth::user();
            $user->notifications()->whereNull('read_at')->update(['read_at' => now()]);
            
            return response()->json([
                'success' => true,
                'message' => 'All notifications marked as read',
                'unread_count' => 0
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'error' => 'Failed to mark all notifications as read'
            ], 500);
        }
    });
});

// Fallback for undefined API routes
Route::fallback(function () {
    return response()->json([
        'error' => 'Endpoint not found',
        'message' => 'The requested API endpoint does not exist'
    ], 404);
});