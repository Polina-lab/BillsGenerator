<?php

namespace App\Models;

use App\Models\Users\User;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TempPassword extends Model
{
    use HasFactory;

    protected $table = "temp_passwords";

    protected $fillable = ["password"];

    public $timestamps = false;

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }


    public function user(){
        return $this->belongsTo(User::class);
    }

}
