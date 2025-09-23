<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\SubscriptionPlan;

class AgentPlan extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="agent_plan";

    public function getagent()
    {
        return $this->belongsTo(SubscriptionPlan::class,'plan_name');
    }
}
