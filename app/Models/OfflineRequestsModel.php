<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\PharmaShop;
use App\Models\User;
use App\Models\SubscriptionPlan;

class OfflineRequestsModel extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="offline_requests";

    public function getPharma()
    {
        return $this->belongsTo(PharmaShop::class, 'pharma_id','id');
    }

    public function getUser()
    {
        return $this->belongsTo(User::class,'submitted_by','id');
    }
    public function getPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan','id');
    }
}
