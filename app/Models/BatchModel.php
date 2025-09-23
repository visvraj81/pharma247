<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\IteamsModel;


class BatchModel extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="batch";
    
    public function getIteam()
    {
        return $this->belongsTo(IteamsModel::class,'item_id');
    }
}
