<?php

namespace App\Models;

use App\Models\Bills\Users;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Coupons extends Model
{
    use HasFactory;
    protected $fillable = ['code','amount_percent','amount_eur','start_date','end_date','usages_remaining','user_id' , 'activated_at' , 'for_who' , 'access_days'];

    public function users(){
        return $this->hasMany( Users::class , 'user_id');
    }
}
