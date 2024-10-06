<?php

namespace App\Models\Api;

use App\Models\Api\ApiModel;
use Illuminate\Database\Eloquent\Model;

class ApiRequest extends Model
{
    public $table = "api_request";

    protected $fillable = ["api_id","type","name", "has_required", "desc"];

    public function api(){
        return $this->belongsTo(ApiModel::class ,'api_id' );
    }

    public function setHasRequiredAttribute($value){
        $this->attributes['has_required'] = boolval($value);
    }


    public $timestamps = false;
}
