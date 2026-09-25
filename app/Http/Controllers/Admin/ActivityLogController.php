<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ActivityLog;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

class ActivityLogController extends Controller
{
    public function index(Request $request)
    {
        try {
            if (! Schema::hasTable('activity_logs')) {
                Schema::create('activity_logs', function (Blueprint $table) {
                    $table->id();
                    $table->foreignId('user_id')->nullable()->constrained('users')->nullOnDelete();
                    $table->string('user_name')->nullable();
                    $table->string('role', 50)->nullable();
                    $table->string('modul', 100);
                    $table->string('aktivitas', 255);
                    $table->text('deskripsi')->nullable();
                    $table->string('ip_address', 50)->nullable();
                    $table->text('user_agent')->nullable();
                    $table->timestamps();
                });
            }
        } catch (\Exception $e) {
            // Lanjutkan jika ada kendala
        }

        $query = ActivityLog::latest();

        if ($request->filled('role')) {
            $query->where('role', $request->role);
        }

        if ($request->filled('modul')) {
            $query->where('modul', $request->modul);
        }

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function ($q) use ($search) {
                $q->where('user_name', 'like', "%{$search}%")
                    ->orWhere('aktivitas', 'like', "%{$search}%")
                    ->orWhere('deskripsi', 'like', "%{$search}%");
            });
        }

        $logs = $query->paginate(20)->withQueryString();
        $modules = ActivityLog::distinct()->pluck('modul')->filter();

        return view('admin.activity_log.index', compact('logs', 'modules'));
    }

    public function destroy(ActivityLog $activityLog)
    {
        $activityLog->delete();

        return redirect()->route('admin.activity-log.index')->with('success', 'Log aktivitas berhasil dihapus.');
    }

    public function clear()
    {
        try {
            Schema::disableForeignKeyConstraints();
            ActivityLog::query()->delete();
            Schema::enableForeignKeyConstraints();

            return redirect()->route('admin.activity-log.index')->with('success', 'Seluruh riwayat log aktivitas berhasil dibersihkan.');
        } catch (\Exception $e) {
            Schema::enableForeignKeyConstraints();

            return redirect()->route('admin.activity-log.index')->with('error', 'Gagal membersihkan log: '.$e->getMessage());
        }
    }
}
