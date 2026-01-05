<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('log_bimbingan', function (Blueprint $table) {
            $table->string('path_dokumentasi')->nullable()->after('catatan_pembimbing');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('log_bimbingan', function (Blueprint $table) {
            $table->dropColumn('path_dokumentasi');
        });
    }
};
