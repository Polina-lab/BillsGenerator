<?php

namespace App\Models\Bills;

use Illuminate\Database\Eloquent\Model;

class Representative extends Model
{
    protected $fillable = ["name" ];

    protected $table="representatives";

    public function firms(){
        return $this->hasOne(Firms::class , "representatives");
    }

    public $timestamps = false;

}
