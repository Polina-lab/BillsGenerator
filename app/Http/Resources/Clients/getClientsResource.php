<?php

namespace App\Http\Resources\Clients;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class getClientsResource extends JsonResource
{

    public static $wrap = '';
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $resData = [];

        foreach($this->resource as $r) {
            $data = [
                "id" => $r->id,
                //"company_name" => $r->company_name,/* ? */
                //"firstname" => $r->firstname ,
                //"lastname" => $r->lastname ,
                "type" => $r->type,
               // "address"=> $r->address ,
               // "id_code" => $r->id_code,
               // "comment"=> $r->comment ,
                "client"=> $r->client,
               // "link" => $r->link,
                "status" => $r->status,
               // "lepping" => $r->lepping,
               // "agent" => $r->agent,
                "work_status" => $r->work_status ,
                "phone" => $r->phone,
              //  "reg_num" => $r->reg_num,
                "email"=> $r->email,
              //  "show_in_bills" => $r->show_in_bills,
                "created_at" => Carbon::parse($r->created_at)->format('Y-m-d'),
                "updated_at" => Carbon::parse($r->upadted_at)->format('Y-m-d'),
                "username" => $r->username,
                "companies" => $r->companies
            ];
            array_push($resData , $data);
        }
        return  $resData;
    }
}
