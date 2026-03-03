<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

use Illuminate\Database\Eloquent\SoftDeletes;

class User extends Authenticatable
{
    use HasFactory, Notifiable, SoftDeletes;

    const ROLE_RECEPTION = 'reception';
    const ROLE_ADMIN = 'admin';
    const ROLE_DOCTOR = 'doctor';
    const ROLE_LAB_STAFF = 'lab_staff';
    const ROLE_PHARMACIST = 'pharmacist';
    const ROLE_PATIENT = 'patient';

    /**
     * Mass assignable attributes
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'role',

        // Patient details
        'gender',
        'dob',
        'blood_group',
        'mobile',
        'address',
        'emergency_contact',
    ];

    /**
     * Hidden attributes
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Attribute casting
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
        'password' => 'hashed',
        'dob' => 'date',
    ];

    public function isAdmin()
    {
        return $this->role === self::ROLE_ADMIN;
    }
    public function isReception()
    {
        return $this->role === self::ROLE_RECEPTION;
    }
    public function isDoctor()
    {
        return $this->role === self::ROLE_DOCTOR;
    }
    public function isLabStaff()
    {
        return $this->role === self::ROLE_LAB_STAFF;
    }
    public function isPharmacist()
    {
        return $this->role === self::ROLE_PHARMACIST;
    }
    public function isPatient()
    {
        return $this->role === self::ROLE_PATIENT;
    }

    /**
     * Patient → Appointments relationship
     */
    public function appointments()
    {
        return $this->hasMany(\App\Models\Appointment::class, 'user_id');
    }

    public function doctor()
    {
        return $this->hasOne(Doctor::class);
    }
    public function labStaff()
    {
        return $this->hasOne(LabStaff::class);
    }
    public function pharmacist()
    {
        return $this->hasOne(Pharmacist::class);
    }
    public function patient()
    {
        return $this->hasOne(Patient::class);
    }
}
