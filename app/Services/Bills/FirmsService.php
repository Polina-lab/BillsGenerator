<?php

namespace App\Services\Bills;
use App\Models\Bills\BankDetails;
use App\Models\Bills\FirmsVat;
use App\Models\Bills\Representative;
use App\Models\Bills\Firms;
use App\Services\File\ImageService;

class FirmsService
{
    protected  $firm;
    protected  $repr;
    protected  $msg;
    protected  $bank;
    protected  $image;
    protected  $vat;

    public function __construct( Firms $f , Representative $r ,FirmsVat $v, BankDetails $b , ImageService $i){
        $this->firm = $f;
        $this->repr = $r;
        $this->bank = $b;
        $this->image = $i;
        $this->vat = $v;
        $this->msg = [];
    }

    public function getAll(){
       return  $this->firm->with("banks" , "vat" )->get();
    }

    public function getActive(){
      return  $this->firm->where("status" , 1)->with("banks" , "vat" )->get();
    }

    public function getAllRepresentatives(){
        return $this->repr->all();
    }


    /** method to check main_firm
     * @param $request
     * @return bool
     */
    protected function checkMainFirm($request): bool
    {
        $res = false;
        $firms = $this->firm->where('main_firm')->get();
        if ($request->has('main_firm') && $request->get('main_firm') === true) {
            if ($firms->count() > 0) {
                foreach ($firms as $f) {
                    $f->update(['main_firm' => false]);
                }
            }
            $res =  true;
        } else if ($firms->count() === 0) {
            $res = true;
        }
        return $res;
    }

    /** check main firm
     * @param $request
     * @return array
     */

    public function create($request){
            $hasFirm =  $this->firm->where( "name" ,$request->name)->count();
            if($hasFirm > 0){
                $this->msg = ["code" => 500 , "msg" => "Firm with name ". $request->name ." was created " , "type" =>"error"] ;
            }else {
                $request->merge(["main_firm" => $this->checkMainFirm($request)]);
                try {

                    if (isset($request->logo) && strlen($request->logo) > 50 ) {
                        $path = $this->image->store_image($request->logo, "/images/firms/" . str_replace(" ", "_", $request->name) . '_' . auth()->user()->id);
                        $request->merge(["logo" => '/' . $path]);
                    }
                } catch (\Exception $exception) {
                    info($exception->getMessage());
                    return $this->msg = ["code" => 500, "msg" => "Oops : image type not supported" , "type" => "error"];
                }

                $firm = $this->firm->create($request->all());
                if (isset($request->banks)) {
                    foreach ($request->banks as $bank) {
                        $this->bank->create(array_merge($bank, ["firm_id" => $firm->id]));
                    }
                }
                if (isset($request->vat)) {
                    foreach ($request->vat as $vat) {
                        $this->vat->create(array_merge($vat, ['firm_id' => $firm->id]));
                    }
                }
                $this->msg = ["code" => 200, "msg" => "Firm Created", "type" => "success", "firm" => $firm->toArray()];

            }
        return $this->msg;
    }


    public function update( int $id , $request){
        $firm = $this->firm->where("id" ,$id)->first();
        if($firm){
            if($request->has('banks')){
                foreach ($request->get('banks') as $b){
                    if(isset($b["id"])){
                        $this->bank->where("id" , $b["id"] )->update($b);
                    }else {
                        $this->bank->create( array_merge($b , ['firm_id' => $firm->id]));
                    }
                }
            }
            if($request->has('vat')) {
                foreach ($request->get('vat') as $v)
                    if (isset($v["id"])) {
                        $this->vat->where("id", $v["id"])->update($v);
                    } else {
                        $this->vat->create(array_merge($v, ['firm_id' => $firm->id]));
                    }
            }

            $request->merge(['main_firm' => $this->checkMainFirm($request)]);

            try {
                if (isset($request->logo) && strlen($request->logo) > 20 && $firm->logo !== $request->logo)  {
                    $path = $this->image->store_image($request->logo, "/images/firms/" . str_replace(" ", "_", $request->name) . '_' . auth()->user()->id);
                    $request->merge(["logo" => '/' . $path]);
                }
            } catch (\Exception $exception) {
                info($exception->getMessage());
                return $this->msg = ["code" => 500, "msg" => "Oops : image type not supported" , "type" => "error"];
            }


            $firm->update($request->all());
            $this->msg = ["code" => 200 , "msg" => "Firm updated" , "type"=> "success"];
        }else{
            $this->msg = ["code" => 500 , "msg" => "Oops: firm not found" , "type"=> "error"];
        }
        return $this->msg;
    }

    public function delete(int $id): array{
        $firm = $this->firm->where("id" ,$id)->first();
        if($firm){
            if($firm->status !== 1) {
                $firm->banks()->delete();
                $firm->vat()->delete();
                $firm->delete();
                $this->msg = ['code' => '200', "msg" => "Firms deleted", "type" => "success"];
            }else {
                $firm->update(["status" => 0]);
                $this->msg = ['code' => '206' ,"msg" => "Firms status set unactive" , "type" => "success"];
            }
        }else{
            $this->msg = ['code' => '404' , "msg" => "Firms not found" , "type" => "error"];
        }
        return $this->msg;
    }

    /** That method to delete bank from bank_details
     * @param int $id
     * @return array
     */

    public function delete_bank(int $id) : array{
        $bank=  $this->bank->where("id" , $id )->first();
        if($bank){
            $bank->delete();
            $this->msg= [ 'code' => 200 , 'msg' => "Deleted" , 'type' => 'success' ];
        }else{
            $this->msg = ['code' => 500 , 'msg' => 'Bank not found' , 'type' => 'error'];
        }
        return $this->msg ;
    }
}
