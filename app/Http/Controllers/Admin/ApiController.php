<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\HasDigitIdRequest;
use App\Http\Resources\ArrayTranducerWData;
use App\Repositories\Api\ApiRep;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;


class ApiController extends Controller
{
    protected $api;

    public function __construct(ApiRep $a){
        $this->api = $a;
    }

    public function index(Request $request){
        return $this->api->getAll($request);
    }


    public function destroy(HasDigitIdRequest $id){
        return $this->api->delete($id->id);
    }

    /** to delete request
     * @param HasDigitIdRequest $id
     * @return array
     */

    public function delete_req(HasDigitIdRequest $id){
        return $this->api->delete_req($id->id);
    }

    /**
     * @param HasDigitIdRequest $id
     * @return mixed
     */


    public function delete_resp(HasDigitIdRequest $id){
        return $this->api->delete_resp($id->id);
    }


    /** to update current
     * @param $id
     * @param Request $request
     * @return array|mixed
     */

    public function update($id , Request $request){
        return $this->api->update($id , $request->all());
    }

    /** to show current
     * @param HasDigitIdRequest $id
     * @return mixed
     */

    public function show(HasDigitIdRequest $id){
        return $this->api->getById($id->id);
    }

    public function reload(){
        $routeCollection = Route::getRoutes();
        $added_count = 0;
        foreach ($routeCollection as $value) {

            if($value) {
                switch ($value->methods) {
                    case in_array("GET", $value->methods) :
                        $method = "GET";
                        break;
                    case in_array("POST", $value->methods) :
                        $method = "POST";
                        break;
                    case in_array("PUT", $value->methods):
                        $method = "PUT";
                        break;
                    case in_array("PATCH", $value->methods):
                        $method = "PATCH";
                        break;
                    case in_array("DELETE", $value->methods):
                        $method = "DELETE";
                        break;
                    default :
                        $method = "GET";
                        break;
                }

                $data = (isset($value->action["middleware"]) & gettype($value->action["middleware"]) === "array") ? implode(",",  $value->action["middleware"]) : "";
                $has  = $this->api->findBy($method  , $value->uri );
                if($has->count() === 0 ) {
                    if ($value->uri) {
                        $this->api->create(["method" => $method, "name" => $value->action["as"]  ?? "--","desc" =>"[ middleware => '" . $data ."']",   "url" => $value->uri,
                            "example" => "",
                            "user_id" => 1]);
                        $added_count +=1;
                    }
                }
            }
        }
        $msg = "Done ! Added ". $added_count . " routes .";
        return new ArrayTranducerWData(['code' => 200 , "msg" => $msg , "type" => "success"]);
    }


}
