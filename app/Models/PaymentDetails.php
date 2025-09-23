<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\User;

class PaymentDetails extends Model
{
    use HasFactory;
    protected $table="payment_details";

    public function getUser()
    {
        return $this->belongsTo(User::class,'party');
    }
}
