<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\SubscriptionPlan;

class PharmaPlan extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="pharma_plan";

    public function getPharmaPlan()       {
        return $this->hasOne(SubscriptionPlan::class, 'id','subscription_plan_id');
    }
}
