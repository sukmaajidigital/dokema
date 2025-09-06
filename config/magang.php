<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Magang Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration for internship management system
    |
    */

    'max_quota' => env('MAGANG_MAX_QUOTA', 20),

    'auto_assign_supervisor' => env('MAGANG_AUTO_ASSIGN', true),

    'notification_enabled' => env('MAGANG_NOTIFICATION', true),

    'workflow_statuses' => [
        'draft' => 'Draft',
        'submitted' => 'Submitted',
        'under_review' => 'Under Review',
        'approved' => 'Approved',
        'rejected' => 'Rejected',
        'in_progress' => 'In Progress',
        'completed' => 'Completed',
        'evaluated' => 'Evaluated'
    ],

    'document_types' => [
        'surat_permohonan' => 'Surat Permohonan',
        'surat_balasan' => 'Surat Balasan',
        'surat_nilai' => 'Surat Nilai',
        'laporan_kegiatan' => 'Laporan Kegiatan'
    ]
];
