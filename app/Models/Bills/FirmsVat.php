<?php

namespace App\Models\Bills;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class FirmsVat extends Model
{
    use HasFactory;
    protected $table  = "firms_vat";
    public $timestamps = false;
    protected $fillable = ['vat' ,  'default' , 'firm_id'];

    public static function boot()
    {
        parent::boot();
        static::updating(function ($item) {
            if($item->default === true)
                FirmsVat::where([
                    ['firm_id', '=', $item->film_id],
                    ['default', '=', 'true']
                ])->update(['default' => false]);
        });
    }

    public function firms(){
        return $this->belongsTo('firms' , 'firm_id');
    }



}
