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
        Schema::table('data_magang', function (Blueprint $table) {
            // Add workflow tracking fields
            $table->text('alasan_penolakan')->nullable()->after('status');
            $table->timestamp('tanggal_persetujuan')->nullable()->after('alasan_penolakan');
            $table->timestamp('tanggal_penolakan')->nullable()->after('tanggal_persetujuan');
            $table->integer('kuota_tersedia')->default(20)->after('tanggal_penolakan');

            // Add workflow status tracking
            $table->enum('workflow_status', [
                'draft',
                'submitted',
                'under_review',
                'approved',
                'rejected',
                'in_progress',
                'completed',
                'evaluated'
            ])->default('draft')->after('status');
        });

        // Add notification/alert system
        Schema::create('workflow_notifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained('users')->onDelete('cascade');
            $table->foreignId('data_magang_id')->nullable()->constrained('data_magang')->onDelete('cascade');
            $table->string('type'); // 'approval_needed', 'approved', 'rejected', 'reminder'
            $table->string('title');
            $table->text('message');
            $table->boolean('is_read')->default(false);
            $table->timestamp('read_at')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('data_magang', function (Blueprint $table) {
            $table->dropColumn(['alasan_penolakan', 'tanggal_persetujuan', 'tanggal_penolakan', 'kuota_tersedia', 'workflow_status']);
        });

        Schema::dropIfExists('workflow_notifications');
    }
};
