<?php

namespace App\Http\Resources\Clients;

use Illuminate\Http\Resources\Json\JsonResource;

class findByResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request)
    {
        $resData = [];

        foreach($this->resource as $r){
            $data = [];
            if(isset($r->firstname)){
                $data["client_id"] = $r->id;
                $name = $r->firstname . " ". $r->lastname;
                $data["name"] = $name ;
                array_push($resData , $data);
                if($r->companies->count() > 0){
                    foreach ($r->companies as $c){
                     $data["name"] = $name . "  [ " . $c->name . " ] ";
                     $data["client_id"] = $r->id;
                     $data["company_id"] = $c->id;
                     array_push($resData , $data);
                    }
                };
            }else if(isset($r->name)){
                    if($r->clients != null) {
                        $data["name"] = isset($r->clients->firstname) ? $r->clients->fistname : "" . " " . isset($r->clients->lastname) ? $r->clients->lastname : "" . " [ " . $r["name"] . " ] ";
                        $data["client_id"] = $r->clients->id;

                        $data["company_id"] = $r->id;
                        array_push($resData , $data);
                    }
                    /* будем отображать только компании с клиентами
                    else{
                        $data["name"] = $r["name"];
                        $data["client_id"] = null;
                    }*/
            }
        }

        return $resData;
    }
}
