<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\PurchesPayment;

class PurchesPaymentDetails extends Model
{
    use HasFactory;
    protected $table="purches_payment_details";
    
    public function getPurches()
    {
        return $this->belongsTo(PurchesPayment::class,'payment_id','id');
    }
}
