<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Import SoftDeletes trait

class PatientsAddress extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="patient_address";
}
