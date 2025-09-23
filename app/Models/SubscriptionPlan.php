<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\SubscriptionPlanFeatures;

class SubscriptionPlan extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="subscription_plan";

    public function getPlanFeature()       {
        return $this->hasMany(SubscriptionPlanFeatures::class, 'subscription_plan_id');
    }
}
