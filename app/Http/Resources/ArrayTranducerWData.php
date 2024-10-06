<?php


namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;


class ArrayTranducerWData extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public static $wrap = "";


    public function toArray($request)
    {
        return parent::toArray($request);
    }

    public function toResponse($request)
    {
        return parent::toResponse($request)->setStatusCode($this->resource["code"] ?? 200);
    }

}
