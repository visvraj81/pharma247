<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\PurchesModel;

class Distributer extends Model
{
    use HasFactory,SoftDeletes;
    protected $table = 'supplier_details';
    
      public function getPurches()
    {
        return $this->hasMany(PurchesModel::class,'distributor_id');
    }
}
