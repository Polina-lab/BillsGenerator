<?php


namespace App\Repositories\Api;

use App\Models\Api\ApiModel;
use App\Models\Api\ApiResponse;
use App\Models\Api\ApiRequest;
use Illuminate\Database\Eloquent\Builder;

class ApiRep
{
    protected $api;
    protected $response;
    protected $request;
    protected $msg ;

    public function __construct(ApiModel $a ,ApiRequest $req , ApiResponse $res){
        $this->api = $a;
        $this->request = $req;
        $this->response = $res;
        $this->msg  = ["code" => 200 , "msg" => 200 , "type" => "success"];
    }

    /**
     * @param $request
     * @return \Illuminate\Contracts\Pagination\LengthAwarePaginator
     */

    public function getAll($request){
        return  $this->api->with('response' , 'request')
            ->when($request->has('url'), function (Builder $table) use ($request){
                $table->where('url' ,"LIKE", "%" .$request->get('url') . "%");
            })
            ->when($request->has('method'), function (Builder $table) use ($request){
                $table->where('method' ,  $request->get('method') );
            })
            ->when($request->has('name'), function (Builder $table) use ($request){
                $table->where('name' ,"LIKE", "%" .$request->get('anme') . "%");
            })
            ->when($request->has('users'), function (Builder $table) use ($request){
                $table->where('user_id' ,$request->get('users') );
            })
            ->paginate(30);
    }

    /** Method to get info about api
     * @param int $id
     * @return mixed
     */

    public function getById(int $id){
        return  $this->api->where("id" , $id )->first();
    }

    /** This method to get by method and url
     * @param $method
     * @param $url
     * @return mixed
     */

    public function findBy($method  , $url){
        return $this->api->where("method" , $method)->where("url" , $url)->get();
    }

    /** Method to search
     * @param string $data
     * @return mixed
     */

    public function search(string $data){
        return  $this->api->where("url" , $data)->get();
    }

    /** Method to update Data
     * @param int $id
     * @param array $data
     * @return mixed
     */

    public function update(int $id ,array $data): array{
        $api = $this->getById($id);
        if($api) {
            if ($data['request']) {
                foreach ($data['request'] as $r) {
                    if (isset($r['id']) & isset($r['name'])) {
                        $r['has_required'] = boolval($r["has_required"]);
                        if($r["name"] != "") {
                            $this->request->where("id", $r['id'])->update(array_merge($r, ["api_id" => $api->id]));
                        }
                    } else {
                        if($r["name"] != "") {
                            $this->request->create(array_merge($r, ["api_id" => $api->id]));
                        }
                    }
                }
            }
            if($data["response"]){

                foreach ($data['response'] as $r) {
                    if (isset($r['id']) & isset($r['name'])) {
                        if( $r["name"] != "") {
                            $this->response->where("id", $r['id'])->update(array_merge($r, ["api_id" => $api->id]));
                        }
                    } else {
                        if($r["name"] != "") {
                            $this->response->create(array_merge($r, ["api_id" => $api->id]));
                        }
                    }
                }
            }
        }else $this->msg  = ['code' => 404 , 'msg' => "Oops : this api not found", 'type' => "error" ];

        try{
            $data["user_id"] = auth()->id();
            $api->update($data);
            $this->msg = ['code' => 200 , 'msg' => "Ok", 'type' => "success" ];
        }catch (\Exception $exception){
            $this->msg = ['code' => 404 , 'msg' => $exception->getMessage(), 'type' => "error" ];
        }
        return $this->msg;
    }

    /** Method to create new Api
     * @param array $data
     * @return mixed
     */

    public function create(array $data){
        return $this->api->create($data);
    }

    /** Method to delete api
     * @param $id
     * @return mixed
     */

    public function delete(int $id){
        $has = $this->api->where("id" , $id)->first();
        if($has){
            $has->delete();
            $this->msg = ["code" => 200, "msg" => "Deleted" , 'type' => "success"];
        }else{
            $this->msg  = ["code" => 404 , "msg" => "Opps: not found" , 'type' => 'error'];
        }
        return  $this->msg;
    }

    /** to delete request
     * @param int $id
     * @return array
     */

    public function delete_req(int $id){
        $has  =  $this->request->where("id", $id)->first();
        if($has){
            $has->delete();
            $this->msg = ["code" => 200, "msg" => "Deleted" , 'type' => "success"];
        }else{
            $this->msg  = ["code" => 404 , "msg" => "Opps: not found" , 'type' => 'error'];
        }
        return $this->msg;
    }

    /** to delete response
     * @param int $id
     * @return array
     */

    public function delete_resp(int $id){
        $has  =  $this->response->where("id", $id)->first();
        if($has){
            $has->delete();
            $this->msg = ["code" => 200, "msg" => "Deleted" , 'type' => "success"];
        }else{
            $this->msg  = ["code" => 404 , "msg" => "Opps: not found" , 'type' => 'error'];
        }
        return $this->msg;
    }
}
