<?php

namespace App\Models\Bills;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaymentStore extends Model
{
    use HasFactory;

    public $fillable = ['user_id' , 'pay_time' , 'info' , 'price'];

    public $timestamps = 'false';




}
