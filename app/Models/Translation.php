<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Translation extends Model
{
    protected $table = 'translations';
    protected $guarded = array('id', 'created_at', 'updated_at');

    protected $fillable = ["group" , "key" , "en" , "ee" , "ru"];

}

