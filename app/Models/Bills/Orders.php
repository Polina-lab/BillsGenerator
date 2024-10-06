<?php

namespace App\Models\Bills;

use Illuminate\Database\Eloquent\Model;

class Orders extends Model
{
    public $table= "orders";

    protected $fillable = ["name","unit_price","firms_id", 'desc' ,'categories_id', "unit" ];

    public function bills(){
        return $this->belongsToMany(\App\Models\Bills\Bills::class , "pivot_orders_bills" ,   'orders_id' , 'bills_id');
    }

    public function categories(){
        return $this->belongsTo(Categories::class , 'categories_id');
    }

    public function firms(){
        return $this->belongsTo(Firms::class , 'firms_id');
    }

    /*
        protected  $appends = [ "price" => "price" ] ;
    */
    /*
    public function getPriceAttribute(){
        return number_format(($this->unit_price  * $this->amount), 2 , "," , '');
    }*/
}
