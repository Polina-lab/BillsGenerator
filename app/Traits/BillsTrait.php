<?php


namespace App\Traits;

trait BillsTrait
{
    use CheckRole;

    public $default_firm_data = [
        'name' =>  'Glo Real Network OÜ',
        'company_name' => 'Glo Real Network OÜ',
        'reg_num' =>  '14118122',
        'phone' => '+372 555 99 313',
        'address' => 'Estonia pst 5, kab. 304',
        'email' => "admin@gloreal.ee",
        'view_in_invoice' => true,
        'is_footer_visible' => false,
        'logo' => "/images/firms/Gloreal/logo.png",
        'banks'=>[
            'bank_name'=> 'LHV',
            'bank_num'=> 'EE187700771002405343',
            'bank_account' => '',
            'bank_swift' => 'LHVBEE22',
            'bank_address' => 'Tartu mnt 2 Tallinn, 10145, Estonia',
        ],
        'vat' =>[
            [
            'id' => 1,
            'vat' => 20
            ]
        ]
    ];

    //:TODO:
    public function check_Km( $firm_vat ,  $price){

        if($firm_vat != null ){

            $price_k = floatval($price) * floatval($firm_vat) /100;
            $price_km =  number_format( $price_k , 2 ,'.',  '');
            $res =  number_format((floatval($price) + floatval($price_km) ),2 ,'.',  '');
        }else{
            $res= number_format($price,2 ,'.',  '');
        }
        return $res;
    }

    public function checkViewPrice($id , $price){
        if((auth()->id() === $id || $this->check_role('view-bill-price'))) return $price;
        else return (double) 0 ;
    }

    public function checkFormat($num){
        if(!is_numeric($num)){
            $num_r = str_replace(',','.' , $num);
            $res =  number_format( $num_r , 2 ,'.',  '');
        }else $res =  number_format( $num , 2 ,'.',  '');
        return $res;
    }

    protected function get_extension(string $data){
        $extension = explode('.',$data);
        if(gettype($extension) === 'array'){
            return end($extension);
        }else return 'png'; // Oops
    }

    public function getOrders($id ,  $data):array{
        $resData = [];
        foreach ($data as $d){
            $res = [
                "id" => $d->id,
                "bills_id" => $d->bills_id,
                "name" => $d->name,
                "amount" => $d->pivot->amount,
                'desc' => $d->desc,
                "currency" => $d->currency,
                "unit" => $d->unit,
                "price" =>  $this->checkFormat($d->pivot->price),
                "unit_price" => $this->checkFormat($d->unit_price)
            ];
            array_push($resData , $res);
        }
        return $resData;
    }

    public function getCompanies($data){
        return isset($data)  ? [
            "id" => $data->id ?? $data["id"],
            "address" => $data->address ?? $data["address"],
            "client_id" => $data->client_id ?? $data["client_id"],
            "name" => $data->name ?? $data["name"],
            "agent" => $data->agent ?? $data["agent"],
            "email" => $data->email ?? $data["email"],
            "phone" => $data->phone ?? $data["phone"],
            "lepping" => $data->lepping ?? $data["lepping"],
            "reg_num" => $data->reg_num ?? $data["reg_num"],
            //status
            //updated_at
            //user_id
         ] : [];
    }

    public function getClients($data){
        return isset($data)  ? [
            "id" => $data->id ?? $data["id"],
            "address" => $data->address ?? $data["address"],
            "agent" => $data->agent ?? $data["agent"],
            "email" => $data->email ?? $data["email"],
            "firstname" => $data->firstname ?? $data["firstname"],
            "lastname" => $data->lastname ?? $data["lastname"],
            'username' => $data->username ?? $data['username'],
            "phone" => $data->phone ?? $data["phone"],
            "id_code" => $data->id_code ?? $data["id_code"],
            "lepping" => $data->lepping ?? $data["lepping"],
        ] : [];
    }

    public function getVat($data_array): array{
        $res = [];
        foreach ($data_array as $data){
           $res[]= [
            "id" =>   $data['id'] ,
            "vat" => $data['vat'],
            "default" => isset($data['default']) ? boolval($data['default']) : true
            ];
        }
        return  $res;
    }



    public function getFirms( $data) :array{

        return isset($data) ? [
            "id" => $data->id ?? $data["id"] ?? "",
            "name" => $data->name ?? $data["name"] ?? "" ,
            "company_name" => $data->company_name ?? $data["company_name"] ?? "",
            "reg_num" => $data->reg_num ?? $data["reg_num"] ?? "",
            "status" => $data->status ?? $data["status"] ?? "",
            "phone" => $data->phone ?? $data["phone"] ?? "",
            "address" => $data->address ?? $data["address"] ?? "" ,
            "vat" =>  isset($data->vat) ? $this->getVat($data->vat->toArray()) : $this->getVat($data['vat']),
            "representative_custom" => $data->representative_custom ??  $data["representative_custom"] ?? "",
            "representative_name" => $data->representative_name ?? $data["representative_name"] ?? "",
            "email" => $data->email ?? $data['email'] ,
            "footer" => $data->footer ?? $data["footer"] ?? false,
            "logo" => $data->logo ?? $data["logo"] ?? "",
            'main_firm' => $data->main_firm ?? $data['main_firm'] ?? false,
            "view_in_invoice" => $data->view_in_invoice ?? $data["view_in_invoice"],
            "is_footer_visible"	 => $data->is_footer_visible ?? $data["is_footer_visible"],
            "representatives" => $data->representatives ?? null,
            "banks" => isset($data->banks) ? $data->banks->toArray() : $data['banks']  ?? [],
            "logo_blob" => isset($data->logo) ?  $this->getLogo($data->logo) : $this->getLogo($data['logo']) ,

        ] : [];
    }
/*
    public function getBank( $data) : array {
        return[
            "bank_name" => $data->bank->bank_name ?? $data['bank']["bank_name"],
            "bank_account" => $data->bank->bank_account ?? $data['bank']["bank_account"],
            "bank_swift" => $data->bank->bank_swift ?? $data['bank']["bank_swift"],
            "bank_address" => $data->bank->bank_address ?? $data['bank']["bank_address"],
            "bank_num" => $data->bank->bank_num ?? $data['bank']["bank_km_reg_num"],
        ];
    }

    public function getVat($data): array{
        return [


        ];
    }*/

    public function getUser($data) : array{
            return isset($data->id) ? [
            "id" => $data->id ,
            //"office_id" => $data->office_id,
            "email" => $data->email,
            "role" => $data->role,
            "username" => $data->username,
            //"contact_email" => $data->contact_email
            ] : [];
    }


    public function getLogo($data){
        $res = "";
        if ($data) {
            $path = public_path($data);
              if (file_exists($path)) {
                  $data_res = file_get_contents($path);
                  $res = 'data:image/' . $this->get_extension($data) . ';base64, ' . base64_encode($data_res);
              }
            }
            return $res;
        }


}
