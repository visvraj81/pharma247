<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\PharmaShop;
use App\Models\User;
use App\Models\ItemCategory;
use App\Models\Package;
use App\Models\CompanyModel;
use App\Models\UniteTable;
use App\Models\salesDetails;
use App\Models\BatchModel;
use App\Models\DrugGroup;

class IteamsModel extends Model
{
    use HasFactory,SoftDeletes;
    protected $table="iteams";

    public function getPharma()
    {
        return $this->belongsTo(CompanyModel::class,'pharma_shop','id');
    }

    public function getDistibuter()
    {
        return $this->belongsTo(Distributer::class,'distributer_id');
    }

    public function getUnite()
    {
        return $this->belongsTo(UniteTable::class,'unit','id');
    }

    
    public function getCategory()
    {
        return $this->belongsTo(ItemCategory::class,'item_category_id');
    }

    public function getPackage()
    {
        return $this->belongsTo(Package::class,'packing_type');
    }
    
    public function getBtachs()
    {
      return $this->hasMany(BatchModel::class, 'item_id'); // Replace 'item_id' with the correct foreign key name
    }
  
    public function getDrugGroup()
    {
        return $this->belongsTo(DrugGroup::class, 'drug_group');
    }

    
//  adjustStock   
    //   public function salesDetails()
    // {
    //     return $this->hasMany(salesDetails::class);
    // }
    //  public function GetsalesDetails()
    // {
    //     return $this->belongsTo(salesDetails::class,'iteam_id','id');
    // }
}
