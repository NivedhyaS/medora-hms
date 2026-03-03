<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LabTestType extends Model
{
    use HasFactory;

    protected $fillable = ['department', 'test_name'];

    public function parameters()
    {
        return $this->hasMany(LabParameterMaster::class, 'test_type_id');
    }
}
