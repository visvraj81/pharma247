<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Package;

class UniteTable extends Model
{
    use HasFactory;
    protected $table="unit";
    public function getPakcgae()
    {
        return $this->belongsTo(Package::class,'package_id');
    }
}
