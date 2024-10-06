<?php

namespace App\Models\Bills;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Langs extends Model
{
    use HasFactory;

    protected $fillable =['sys_name', 'name', 'is_enabled'];

    public $timestamps = false;

}
