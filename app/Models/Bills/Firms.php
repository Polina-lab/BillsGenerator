<?php

namespace App\Models\Bills;

use Illuminate\Database\Eloquent\Model;

class Firms extends Model
{
    protected $fillable = [
        'name' ,'email', 'company_name',
        'status' ,// active on unactive firm
        "vat_reg_num", "reg_num" , "phone" ,
        "representatives" ,"representative_custom",
        "representative_name" ,"footer" ,
        "is_footer_visible" ,'requisites_visible' ,
        "address" , "vat_num" ,
        'logo', 'view_in_invoice',
        'main_firm'
    ];
    public $timestamps = false;

    // for collumn bank_details
    public function banks(){
        return $this->hasMany(BankDetails::class , "firm_id");
    }

    // for collumn representatives
    public function representatives(){
        return $this->belongsTo(Representative::class , 'representatives');
    }

    public function bills(){
        return $this->hasMany(Bills::class , "firm_id");
    }

    //for vat
    public function vat(){
        return $this->hasMany(FirmsVat::class , 'firm_id');
    }
}
