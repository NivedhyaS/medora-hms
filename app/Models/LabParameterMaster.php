<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabParameterMaster extends Model
{
    use HasFactory;

    protected $table = 'lab_test_parameters_master';

    protected $fillable = [
        'test_type_id',
        'parameter_name',
        'min_value',
        'max_value',
        'unit',
        'is_required',
    ];

    public function testType()
    {
        return $this->belongsTo(LabTestType::class, 'test_type_id');
    }
}
