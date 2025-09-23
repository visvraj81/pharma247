<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\IteamsModel;
use App\Models\SalesModel;

class salesDetails extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="sales_details";

    public function getIteam()
    {
        return $this->belongsTo(IteamsModel::class,'iteam_id');
    }

    public function getSales()
    {
        return $this->belongsTo(SalesModel::class,'sales_id');
    }
}
