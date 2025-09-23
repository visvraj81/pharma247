<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SetPillReminder extends Model
{
    use HasFactory;
    protected $table="set_pill_reminder";
  
  	public function itemNameGet()
    {
     	return $this->hasOne(IteamsModel::class,'id','item_id'); 
    }
}
