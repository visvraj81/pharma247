<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PurchesReturnDetails;
use  App\Models\Distributer;

class PurchesReturn extends Model
{
    use HasFactory;
    protected $table="purches_retuen";

    public function getUser()
    {
        return $this->belongsTo(Distributer::class,'distributor_id');
    }

    public function getPurchesReturn()
    {
        return $this->hasMany(PurchesReturnDetails::class,'purches_id');
    }
}
