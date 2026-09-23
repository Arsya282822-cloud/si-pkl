<?php

use Illuminate\Database\Migrations\Migration;
use App\Services\InstrukturAccountService;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        try {
            InstrukturAccountService::syncAllDudiAccounts('dudi1234');
        } catch (\Throwable $e) {
            // Log or continue
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
