<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class LabStaff extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'lab_staff';

    protected $fillable = [
        'user_id',
        'lab_id',
        'name',
        'email',
        'phone',
        'department',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function labTests()
    {
        return $this->hasMany(LabTest::class);
    }
}
