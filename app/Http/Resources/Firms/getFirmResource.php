<?php

namespace App\Http\Resources\Firms;

use App\Models\Bills\Representative;
use Illuminate\Http\Resources\Json\JsonResource;

class getFirmResource extends JsonResource
{

    public static $wrap = '';

    public function getBanks($data) :array{
        $res = [];
        foreach ($data as $d){
            array_push($res ,[
                            "id" => $d->id  ,
                            "bank_name" => $d->bank_name ,
                            "bank_num" => $d->bank_num,
                            "bank_account" => $d->bank_account,
                            "bank_swift" => $d->bank_swift,
                            "bank_address" => $d->bank_address
                        ]);
        }
        return  $res;
    }



    public function getVat($data) : array{
        $res =['vat' => [] , 'vat_default'=>[]];
        foreach ($data as $d) {
            array_push($res['vat'], [
                'vat' => $d->vat ,
                'default' => boolval($d->default) ?? false ,
                'id' => $d->id
            ]);

            if(boolval( $d->default) === true) {
               $res['vat_default'] = [
                   'vat' => $d->vat,
                   'id' => $d->id
               ];

            }
        }

        return  $res;
    }


    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array
     */
    public function toArray($request){
        $data = [];
        $representatives_list = Representative::all()->toArray();
            foreach($this->resource as $r) {
                $firm_data =  [
                        'id' => $r->id,
                        'name'=> $r->name ,
                        'company_name' => $r->company_name,
                        'status' => $r->status,
                        'reg_num' => $r->reg_num,
                        'phone' => $r->phone ?? '',
                        'address' => $r->address,
                        'vat_reg_num' => $r->vat_reg_num ,
                        'representatives'=> $r->representatives ,
                        'representatives_list' => $representatives_list,
                        'representative_name' => $r->representative_name,
                        'representative_custom' => $r->representative_custom,
                        'footer' => $r->footer ,
                        "requisites_visible" => $r->requisites_visible ,
                        "is_footer_visible" => $r->is_footer_visible ,
                        "logo" => $r->logo,
                        "email" => $r->email,
                        "view_in_invoice" => $r->view_in_invoice,
                        "main_firm" => $r->main_firm,
                        "banks" => $this->getBanks($r->banks) ,
                    ];
                $vat_data = $this->getVat($r->vat);
                $result = array_merge($firm_data , $vat_data);
                array_push($data, $result);
            }
            return  $data;
        }
}
