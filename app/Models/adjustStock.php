<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\IteamsModel;

class adjustStock extends Model
{
    use HasFactory;
    protected $table="adjust_stock";
    
    public function getIteam()
    {
        return $this->belongsTo(IteamsModel::class,'item_name','id');
    }

}
