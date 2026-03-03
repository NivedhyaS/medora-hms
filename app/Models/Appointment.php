<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

use Illuminate\Database\Eloquent\SoftDeletes;

class Appointment extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'doctor_id',
        'patient_id',
        'user_id',
        'appointment_date',
        'appointment_time',
        'token_no',
        'payment_status',
        'consultation_fee',
        'status',
        'diagnosis',
        'clinical_notes',
    ];

    public function billing()
    {
        return $this->hasOne(Billing::class);
    }

    // Appointment belongs to a Doctor
    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    // Appointment belongs to a Patient
    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    // Deprecated: use patient() instead
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function labTests()
    {
        return $this->hasMany(LabTest::class);
    }
}
