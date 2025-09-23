<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\SubscriptionPlan;

class ShopPlan extends Model
{
    use HasFactory;
    protected $table="shop_plan";
    
     public function getagent()
    {
        return $this->belongsTo(SubscriptionPlan::class,'plan');
    }
}
