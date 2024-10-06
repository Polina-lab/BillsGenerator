<?php

namespace App\Http\Resources\Bills;

use App\Models\Clients\Clients;
use App\Models\Clients\Company;
use App\Traits\BillsTrait;
use Illuminate\Http\Resources\Json\JsonResource;

class BillResource extends JsonResource
{
    public static $wrap = '';

    use BillsTrait;


    /**
     * @param $client_id
     * @param $company_id
     * @return array
     */

    protected function getClientWithCompanies($client_id  , $company_id){
        $res = [];
        if($client_id != null){
            $client = Clients::where('id' ,$client_id)->first();
            $res =  $this->getClients($client);
            if($company_id != null){
                $company =  Company::where('id' , $company_id)->first();
                $res['company'] = $this->getCompanies($company);
            }
        }
        if($client_id == null && $company_id != null ){
            $company =  Company::where('id' , $company_id)->first();
            $res['company'] = $this->getCompanies($company);
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
        $resData = [];
        foreach($this->resource as $r) {
            $vat_value = (isset($r->firms->vat) && $r->vat_id !=null ) ?  $r->firms->vat->where('id' , $r->vat_id)->first()->vat : null;

            $data = [
                "id" => $r->id,
                "name" => $r->name,
                "price" => $this->checkViewPrice($r->act_user_id  ,
                    $this->check_Km($vat_value, $r->price)
                ),
                'vat_value' => $this->checkViewPrice($r->act_user_id  ,$vat_value),
                "price_no_km" => $this->checkViewPrice($r->act_user_id  ,  $r->price),
                "number" => $r->number,
                "firm_id" => $r->firm_id,
                "user_id" => $r->user_id,
                "act_user_id" => $r->act_user_id,
                "comment" => $r->comment,
                "deal" => $r->deal,
                "status" => $r->status,
                "was_paid" => $r->was_paid,
                "date" => $r->date,
                "deadline" => $r->deadline,
                "prepaid" => $r->prepaid,
                "penalty" => $r->penalty,
                "paid_cash" => $r->paid_cash,
                "locale" => $r->locale,
                "currency" => $r->currency ,
                "sender_user_id" => $r->sender_user_id ,
                "was_sent" => $r->was_sent,
                "invoice" => $r->invoice,
                "company_id" => $r->company_id ,
                "created_at" => $r->created_at,
                "updated_at" => $r->updated_at,
                "firms" => $this->getFirms($r->firms),
                "users" => $this->getUser($r->users),
                "orders" => $this->getOrders($r->act_user_id, $r->orders),
                "client_id" => $r->client_id,
                "vat_id" => $r->vat_id ?? null,
                "client" => $this->getClientWithCompanies($r->client_id , $r->company_id),
                ];
            array_push($resData , $data);
        }

        return $resData ;
    }
}
