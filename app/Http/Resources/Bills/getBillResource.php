<?php

namespace App\Http\Resources\Bills;

use App\Traits\BillsTrait;
use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;

class getBillResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public static $wrap = '';


    use BillsTrait;

    public function toArray($request)
    {
        $data = [];
         if(isset($this->resource->vat_id) && $this->resource->vat_id !== null && isset($this->resource->firms->vat)) {
             $price_km = floatval($this->resource->price) * floatval($this->resource->firms->vat->where('id' , $this->resource->vat_id )->first()->vat) / 100;
             $data["price_km"] =  number_format( $price_km ,2 , '.', '' ) ;//
             $data["price_no_km"] = number_format($this->resource->price ,2 ,'.',  '');
             $data["price_with_km"] = number_format( floatval($this->resource->price) + floatval($price_km)  ,2 , '.', '');//,',',  ''
         }else if( $this->resource->type == false) {
             $price_km = floatval($this->resource->price) * 20 / 100;
             $data['km_percent'] = 20;
             $data["price_km"] = number_format($price_km, 2, '.', '');
             $data["price_no_km"] = number_format(floatval($this->price) - floatval($price_km), 2, '.', '');//,',',  ''
             $data["price_with_km"] = number_format(floatval($this->price), 2, '.', '');
         }else{
             $data["price_km"] = 0;
             $data["price_no_km"] = number_format($this->resource->price,2 ,'.',  '');
             $data["price_with_km"] = number_format($this->resource->price ,2 ,'.',  '');
         }
        $data["price"] =  number_format( $this->resource->price ,2 , '.', '' ) ;

        $data["act_user_id"] = $this->resource->act_user_id ;
        // $data["client_id"] = $this->resource->client_id ;
        // $data["company_id"] = $this->resource->company_id;
        $data["clients"]= $this->resource->type == true  ? $this->getClients($this->resource->clients) : $this->getFirms($this->resource->firms)  ;
        $data["comment"] = $this->resource->comment ;
        $data["companies"]  = $this->getCompanies($this->resource->companies);
        // $data["created_at"] = $this->resource->created_at;


        $data["date"] = Carbon::parse($this->resource->date)->format('Y-m-d');
        $data["deadline"] = $this->resource->deadline;
        $data["deal"] = $this->resource->deal  ;
        $data["firm_id"] = $this->resource->firm_id;
        $data["firms"] = $this->resource->type == true  ? $this->getFirms($this->resource->firms) : $this->getFirms($this->default_firm_data) ;
        $data["vat_id"] = $this->resource->type == true ? $this->resource->vat_id : 1 ;
        $data["id"] = $this->resource->id;
        $data["invoice"] = $this->resource->invoice;
        $data["locale"] = $this->resource->locale;
        $data["name"] = $this->resource->name;
        $data["number"] = $this->resource->number;
        $data["orders"]= $this->getOrders($this->resource->act_user_id ,$this->resource->orders);
        $data["paid_cash"] = $this->resource->paid_cash ;
        $data["penalty"] = $this->resource->penalty;
        $data["prepaid"] = $this->resource->prepaid ;
        $data["payment_method"] = $this->resource->payment_method;
        $data["sender_user"] = $this->getUser($this->resource->sender_user);
        $data["currency"] = $this->resource->currency;
        $data["sender_user_id"] =  $this->resource->sender_user_id;
        $data["status"] =  $this->resource->status;
        $data["updated_at"] = $this->resource->updated_at;
        $data["user_act"] = $this->getUser($this->resource->user_act);
        $data["user_id"] = $this->resource->user_id;
        $data["users"] = $this->getUser($this->resource->users);
        $data["was_paid"] =  $this->resource->was_paid;
        $data['type'] = boolval($this->resource->type);
        //$data["was_sent"] = $this->resource->was_sent;
        return $data;
    }
}
