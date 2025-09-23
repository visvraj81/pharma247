<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes; // Import SoftDeletes trait

class PatientFamilyRelation extends Model
{
    use HasFactory,HasApiTokens;
    protected $table="patient_family_relation";
}
