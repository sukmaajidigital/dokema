<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Modify enum to add 'selesai' status
        DB::statement("ALTER TABLE data_magang MODIFY COLUMN status ENUM('menunggu', 'diterima', 'ditolak', 'selesai') NOT NULL DEFAULT 'menunggu'");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Revert back to original enum values
        DB::statement("ALTER TABLE data_magang MODIFY COLUMN status ENUM('menunggu', 'diterima', 'ditolak') NOT NULL DEFAULT 'menunggu'");
    }
};
