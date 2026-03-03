<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UploadedPrescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'patient_id',
        'file_path',
        'patient_note',
        'pharmacist_note',
        'status',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }
}
