<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FailureInvestigationAuditTrail extends Model
{
    use HasFactory;
    protected $table = 'failure_investigation_audit_trails';

    public function failureInvestigation() {
        return $this->belongsTo(FailureInvestigation::class);
    }
}
