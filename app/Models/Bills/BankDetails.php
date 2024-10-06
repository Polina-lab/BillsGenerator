<?php

namespace App\Models\Bills;

use App\Models\Bills\Firms;
use Illuminate\Database\Eloquent\Model;

class BankDetails extends Model
{
    protected $fillable = ["bank_name" ,"bank_num", "bank_account", "bank_swift", "bank_address" , "firm_id"];
    protected $table = "bank_details";
    public $timestamps = false;

    public function firms(){
        return $this->hasMany(Firms::class , 'firm_id');
    }


}
