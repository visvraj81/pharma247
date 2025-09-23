<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\IteamsModel;
use App\Models\getPurchesReturn;

class PurchesReturnDetails extends Model
{
    use HasFactory;
    protected $table="purches_return_details";

    public function IteamsModel()
    {
        return $this->belongsTo(IteamsModel::class,'iteam_id');
    }
    
    public function getPurchesReturn()
    {
        return $this->belongsTo(PurchesReturn::class,'purches_id');
    }
}
