<?php

namespace App\Models\Api;

use App\Models\Api\ApiModel;
use Illuminate\Database\Eloquent\Model;

class ApiResponse extends Model
{
    public $table = "api_response";
    protected $fillable = ["id", "api_id", "type", "name", "desc", "example"];

    public function api(){
        return $this->belongsTo(ApiModel::class ,'api_id' );
    }

    public $timestamps = false;
}
