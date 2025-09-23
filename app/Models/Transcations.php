<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\PharmaShop;

class Transcations extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="transcations";

    public function getPharmaPlan()       {
        return $this->hasOne(PharmaShop::class, 'id','pharma_name');
    }
}
