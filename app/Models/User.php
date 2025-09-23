<?php
namespace App\Models;
  
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use App\Models\AgentPlan;
use App\Models\Distributer;
use App\Models\SalesModel;
use App\Models\PurchesModel;
  
class User extends Authenticatable
{
     use HasFactory, SoftDeletes, Notifiable, HasRoles, HasApiTokens; // Add HasApiTokens here
  
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
    ];
  
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];
  
    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function getplan()
    {
        return $this->hasMany(AgentPlan::class,'agent_id');
    }
    public function getdistributer()
    {
        return $this->hasOne(Distributer::class,'distributer_id');
    }

    public function getSalesList()
    {
        return $this->hasMany(SalesModel::class,'doctor_id');
    }

    public function getPurches()
    {
        return $this->hasMany(PurchesModel::class,'distributor_id');
    }
}
