<?php

namespace App\Models\Bills;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Categories extends Model
{
    use HasFactory;

    protected $fillable = ['name'  , 'firms_id' ,'order' , 'parent_id' ];

    protected  $appends = [ "orders" => "orders_count" ] ;


    public function children(){
        return $this->hasMany(   Categories::class, 'parent_id', 'id')->orderBy('order' , "ASC")->with('children');
    }

    public function firms(){
        return $this->belongsTo(Firms::class, 'firms_id');
    }

    public function getOrdersCountAttribute(){
        return $this->orders()->count();
    }

    public function orders(){
        return $this->belongsTo( Orders::class , "category_id");
    }

    public $timestamps = false;

}
