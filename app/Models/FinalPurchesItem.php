<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\PurchesModel;
use App\Models\PurchesDetails;

class FinalPurchesItem extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="final_purches_item";

    public function getParches()
    {
      return $this->belongsTo(PurchesModel::class,'purches_id','id');
    }
    
   
}