<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class WorkflowTransition extends Model
{
    protected $fillable = [
        'data_magang_id',
        'from_status',
        'to_status',
        'triggered_by',
        'notes',
        'metadata',
    ];

    protected $casts = [
        'metadata' => 'array',
    ];

    public function dataMagang()
    {
        return $this->belongsTo(DataMagang::class);
    }

    public function triggeredBy()
    {
        return $this->belongsTo(User::class, 'triggered_by');
    }

    /**
     * Log a workflow transition
     */
    public static function log(DataMagang $magang, ?string $notes = null, ?array $metadata = null)
    {
        return self::create([
            'data_magang_id' => $magang->id,
            'from_status' => $magang->getOriginal('workflow_status'),
            'to_status' => $magang->workflow_status,
            'triggered_by' => Auth::id(),
            'notes' => $notes,
            'metadata' => $metadata,
        ]);
    }
}
