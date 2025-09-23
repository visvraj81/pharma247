<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\User;
use App\Models\salesDetails;
use App\Models\PaymentModel;
use App\Models\SalesReturn;
use App\Models\CustomerModel;
use PhpParser\Node\Stmt\Return_;
use App\Models\DoctorModel;

class SalesModel extends Model
{
    use HasFactory,SoftDeletes;
     protected $table="sales";

     public function getUserName()
     {
        return $this->belongsTo(CustomerModel::class,'customer_id');
     }

     public function getDoctor()
     {
      return $this->belongsTo(DoctorModel::class,'doctor_id');
     }

     public function getSales()
     {
      return $this->hasMany(salesDetails::class,'sales_id');
     }

     public function getSalesDetails()
     {
      return $this->belongsTo(salesDetails::class,'id','sales_id');
     }

     public function getPayment()
     {
      return $this->belongsTo(PaymentModel::class,'payment_name');
     }

     public function salesReturn()
     {
       return $this->belongsTo(SalesReturn::class,'bill_no','bill_no');
     }
}
