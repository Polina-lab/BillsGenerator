<?php


namespace App\Services;


use App\Repositories\Bills\ClientsRep;
use App\Repositories\Bills\CompanyRep;
use Illuminate\Database\Eloquent\Collection;

class ClientService
{

    protected $client;
    protected $msg;
    protected $companies;

    public function __construct(ClientsRep  $c , CompanyRep $comp ){
        $this->client = $c;
        $this->companies = $comp;
        $this->msg = ["msg" => "Ok" , "code" => 200 , "type" => "success"];
    }

    /** Method to get all Clients
     * @return \App\Models\Clients\Clients[]|\Illuminate\Database\Eloquent\Builder[]|Collection
     */

    public function getAll($request){
        return $this->client->getAll($request);
    }


    /** method to find only by client
     * @param $request
     * @return array|\Illuminate\Database\Eloquent\Builder[]|Collection
     */

    public function findByClient($request){
        return $this->client->findByClient($request);
    }


    /** Method to find clients or companies by name
     * @param $request
     * @return Collection
     */

    public function findBy($request){
        return $this->client->findBy($request)->merge($this->companies->findBy($request));
    }

    /** Method to find client by Id
     * @param int $id
     * @return mixed
     */

    public function getById(int $id){
        return $this->client->getById($id);
    }

    /** Method to create Client
     * @param  array
     * @return array
        array:4 [
          "msg" => "Ok"
          "code" => 200
          "type" => "success"
          "id" => 406
        ]
     **/

    public function create(array $request) : array{
        $client = $this->client->create($request );
        if($client) {
            if (isset($request["companies"]) && sizeof($request["companies"]) > 0 ) {
                foreach ($request["companies"] as $comp) {

                    if(isset($comp["name"]) && $comp["name"] != null) {
                        $this->companies->create(array_merge($comp, ["client_id" => $client->id]));
                    }
                }
            }

            $this->msg["id"] = $client->id;
        }else{
            $this->msg = ["msg" => "Oops: client not created" , "code" => 400 , "type" => "error"];
        }


        return $this->msg;
    }

    /** Method to create or update Client
     * @param array $data
     * @return array
     *
        array:4 [
        "msg" => "Ok"
        "code" => 200
        "type" => "success"
        "id" => 406
        ]
     */

    public function createOrUpdateClient(array $data) : array{

        if (isset($data["id"]) & $data["id"] != null) {
                $this->msg = $this->update($data["id"], $data);
            }else{
                if (isset($data["firstname"])
                    & isset($data["lastname"])
                    & $data["firstname"] != null
                    & $data["lastname"] != null)
                    $this->msg = $this->create($data);
            }
        return  $this->msg;
    }

    /** Method to create or update Company
     * @param array $data
     * @return array
     */

    public function createOrUpdateCompany(array $data ) : array{
        $req = $this->companies->updateOrCreate($data);

        if(isset($req->id)){
            $this->msg["id"] = $req->id;
        }else if(isset($data["id"])){
            $this->msg["id"] = (integer) $data["id"];
        }else {
            $this->msg["id"] = null;
        }
        return  $this->msg;
    }


    /** Method to update Client
     * @param int $id
     * @param $data
     * @return array
     */
    public function update(int $id ,  array $data) : array{

        if(!$this->client->update( $id , $data)){
            $this->msg = ["msg" => "Oops : cant update" , "code" => 500 , "type" => "error" ];
        }else
            $this->msg["id"] = $id;

        if(isset($data["companies"])){
            foreach ($data["companies"] as $cat){
                $this->companies->updateOrCreate(array_merge($cat , ["client_id" => $id]));
            }
        }
        return $this->msg;
    }

    /** Method to delete Client
     * @param int $id
     * @return array
     */

    public function delete(int $id) : array{
        $client = $this->getById($id);
        if($client->bills->count() > 0){
            $this->msg = ['msg' => 'That client uses in invoices. I cant to delete ', 'code' => '500' , 'type' =>'error'];
        }else {
            if ($client->companies) {
                foreach ($client->companies as $company) {
                    $this->companies->delete($company->id);
                }
            }
            if (!$this->client->delete($id)) {
                $this->msg = ["msg" => "Oops : cant delete", "code" => 500, "type" => "error"];
            }
        }
        return $this->msg;
    }
}
