<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Pharmacist extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'user_id',
        'pharm_id',
        'name',
        'email',
        'phone',
        'gender',
        'dob',
        'address',
        'emergency_contact',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
