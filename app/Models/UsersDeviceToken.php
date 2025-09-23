<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UsersDeviceToken extends Model
{
    use HasFactory, SoftDeletes;
    protected $table = "users_device_token";
}
