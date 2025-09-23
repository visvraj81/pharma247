<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;
use Illuminate\Database\Eloquent\SoftDeletes;

class PharmaShop extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="pharma_shop";

    public function getUser()       {
        return $this->hasOne(User::class, 'id','user_id');
    }

    public function getAgent()       {
        return $this->hasOne(User::class, 'id','agent_id');
    }
    
}
