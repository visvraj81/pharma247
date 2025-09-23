<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SetRefillReminder extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="set_refill_reminder";
  
  	public function itemNameGet()
    {
     	return $this->hasOne(IteamsModel::class,'id','item_id'); 
    }
}
