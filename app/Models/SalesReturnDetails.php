<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\IteamsModel;
use App\Models\SalesModel;
use App\Models\SalesReturn;

class SalesReturnDetails extends Model
{
    use HasFactory;
    protected $table="sales_return_details";

    public function getIteamName()
    {
        return $this->belongsTo(IteamsModel::class,'iteam_id');
    }

    public function getSales()
    {
        return $this->belongsTo(SalesReturn::class,'sales_id');
    }
}
