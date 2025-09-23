<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\SalesReturnDetails;
use App\Models\SalesModel;
use App\Models\DoctorModel;
use App\Models\CustomerModel;

class SalesReturn extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="sales_return";

    public function salesReturnGet()
    {
        return $this->hasMany(SalesReturnDetails::class,'sales_id');
    }

    public function salesReturnSingle()
    {
        return $this->hasMany(SalesReturnDetails::class,'id','sales_id');
    }

    public function getUserName()
    {
        return $this->belongsTo(CustomerModel::class,'customer_id');
    }

    public function getSales()
    {
        return $this->belongsTo(SalesModel::class,'bill_no','bill_no');
    }
    
    public function getDoctor()
    {
      return $this->belongsTo(DoctorModel::class,'doctor_id');
    }
}
