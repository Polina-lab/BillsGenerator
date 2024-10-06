<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use App\Models\Api\ApiRequest;
use App\Models\Api\ApiResponse;

class ApiModel extends Model
{
    public $table = "api";

    protected $fillable = ["method", "name", "url", "desc", "example", "user_id"];

    public function request(){
        return $this->hasMany(ApiRequest::class , 'api_id');
    }

    public function response(){
        return $this->hasMany(ApiResponse::class , 'api_id');
    }


}
