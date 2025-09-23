<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes; // Import SoftDeletes trait

class PatientsFamilyModel extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="patients_family";
}
