<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabReportValue extends Model
{
    use HasFactory;

    protected $fillable = [
        'report_id',
        'parameter_id',
        'value',
        'status',
    ];

    public function report()
    {
        return $this->belongsTo(LabTest::class, 'report_id');
    }

    public function parameter()
    {
        return $this->belongsTo(LabParameterMaster::class, 'parameter_id');
    }
}
