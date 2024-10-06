<?php

namespace App\Http\Resources\User;

use App\Http\Resources\ArrayTransducer;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use App\Http\Resources\ArrayTranducer;

class getUserResource extends JsonResource
{


    public static $wrap = "";

    /** Function to check username
     * @param $data
     * @return string
     */

    protected function getUserName($data) : string {
           return (isset($data->firstname) & isset($data->lastname)) ? $data->firstname  ." ". $data->lastname : '' ;
    }

    /** Function to return user permissions
     * @param $data
     * return string
     */

    protected function getPermissions($data) : array{
        $res = [];
        foreach ($data as $d){
            array_push($res , $d->sys_name);
        }
        return  $res;
    }

    protected function getAvailableTeams($data) :array{
        $res = [];
        foreach($data as $d){
            array_push($res ,
            ["id" => $d->id,
            "key" => $d->key ,
            "name" => $d->name ?? "",
            "active_until" => $d->active_until,
            "payment_plan_id" =>$d->payment_plan_id,
            'timezone' => $d->timezone,
            'currency' => $d->currency,
            "current_balance" => $d->current_balance,
            ]);
        }
        return  $res;
    }


    /** to get User data
     * @param $data
     * @return array
     */

    public function getDataField($data){
        $res = [];
        if($data){
                $res["email"] =  $data->email;
                $res["email_verified_at"] = $data->email_verified_at;
                $res["role"] =  isset($data->local_data->roles) ? ['name' => $data->local_data->roles->sys_name , "id" => $data->local_data->roles->id ] : [];
                $res["is_enabled"] = $data->is_enabled ?? true;
                $res["has_multi"] = $data->has_multi;
                $res["has_buyer"] = $data->has_buyer;
                $res['last_login'] = Carbon::parse($data->last_login)->format("Y-m-d H:i:s") ?? null;
                //$res["created_at"] = $data->created_at;
                $res['default_team'] = $data->default_team;
                $res['disable_suggestions'] = $data->disable_suggestions;
                $res["available_teams"] = isset($data->available_teams) ? $this->getAvailableTeams($data->available_teams) : [];
                $res["permissions"] = isset($data->local_data->roles) ? $this->getPermissions($data->local_data->roles->permissions) : [];
                $res["username"] = $this->getUserName($data->local_data);
                $res["local_data"] = $data->local_data ?? [];
                if($data->extra_info){
                    $res['extra_info'] = $data->extra_info;
                }
        }
        return $res;
    }


    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */

    public function toArray($request)
    {

        if(isset($this->resource['user']) && is_object( $this->resource['user']))
            $res =  [
                "user" =>  $this->getDataField($this->resource['user']) ?? [],
                "msg" =>  $this->resource["msg"] ?? "",
                "type" => $this->resource["type"] ?? "",
                "code" => $this->resource["code"] ?? "",
            ];
        else $res =  new ArrayTransducer($this->resource);
        return  $res;
    }

}
