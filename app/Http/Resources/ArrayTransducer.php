<?php

namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class ArrayTransducer extends JsonResource
{

    public static $wrap = "";

    public function toArray($request)
    {
        return parent::toArray($request);
    }

    public function toResponse($request)
    {
        return parent::toResponse($request)->setStatusCode($this->resource["code"]);
    }
}
