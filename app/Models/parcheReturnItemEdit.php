<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class parcheReturnItemEdit extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="parches_return_item_edit";
}
