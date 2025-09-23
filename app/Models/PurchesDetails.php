<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\IteamsModel;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\PurchesModel;
use App\Models\Distributer;

class PurchesDetails extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="purches_details";

    public function getIteam()
    {
        return $this->belongsTo(IteamsModel::class,'iteam_id');
    }

    public function getpurches()
    {
        return $this->belongsTo(PurchesModel::class,'purches_id');
    }
     public function getpurchesData()
    {
        return $this->belongsTo(PurchesModel::class,'purches_id','id');
    }
    
    public function getUser()
    {
        return $this->belongsTo(Distributer::class,'distributor_id');
    }
}
