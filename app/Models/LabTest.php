<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LabTest extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'lab_tests';

    protected $fillable = [
        'doctor_id',
        'patient_id',
        'appointment_id',
        'lab_staff_id',
        'test_type_id',
        'department',
        'test_name',
        'instructions',
        'result',
        'remarks',
        'file_path',
        'status',
        'requested_at',
    ];

    public function testType()
    {
        return $this->belongsTo(LabTestType::class, 'test_type_id');
    }

    public function parameterValues()
    {
        return $this->hasMany(LabReportValue::class, 'report_id');
    }

    protected $casts = [
        'requested_at' => 'datetime',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }

    public function labStaff()
    {
        return $this->belongsTo(LabStaff::class);
    }
}
