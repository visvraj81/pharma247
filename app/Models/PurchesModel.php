<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PurchesDetails;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\IteamsModel;
use App\Models\Distributer;

class PurchesModel extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="purches";

    public function getPurchesDetails()
    {
        return $this->hasMany(PurchesDetails::class,'purches_id');
    }

    public function getPurches()
    {
        return $this->belongsTo(PurchesDetails::class,'id','purches_id');
    }

    public function getUser()
    {
        return $this->belongsTo(User::class,'distributor_id');
    }
    
    public function getdistributer()
    {
        return $this->belongsTo(Distributer::class,'distributor_id','distributer_id');
    }


   
}
