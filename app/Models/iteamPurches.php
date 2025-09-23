<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\UniteTable;
use App\Models\GstModel;
use Illuminate\Database\Eloquent\SoftDeletes;
class iteamPurches extends Model
{
    use HasFactory, SoftDeletes;
    protected $table="purchase_item";
    
    public function getUnite()
    {
        return $this->belongsTo(UniteTable::class,'unit','id');
    }

    public function getGST()
    {
        return $this->belongsTo(UniteTable::class,'gst','id');
    }
}
