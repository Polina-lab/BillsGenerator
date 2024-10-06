<?php


namespace App\Repositories\Bills;


use App\Models\Clients\Clients;
use Illuminate\Database\Eloquent\Builder;
class ClientsRep
{

    protected  $client;
    protected $msg;
    public function __construct( Clients $c)
    {
        $this->client = $c;
        //       $this->msg = ["msg" => "Created" , "code" => '200' , "type" => "success" ];
    }

    protected function getFirst($id){
       return $this->client->where("id" , $id)->first();
    }


    public function findByClient($request){
        return $this->client->with("companies")
            ->when($request->has('active'), function (Builder $query) use ($request) {
                $query->where('status' ,    (integer) $request->get('active'));
            })->when(!$request->has('active'), function (Builder $query) use ($request) {
                $query->where('status' ,    1);
            })->when($request->has('firstname'), function (Builder $query) use ($request) {
                $query->where("firstname", 'LIKE', "%" . $request->get('firstname') . "%");
            })->when($request->has('lastname'), function (Builder $query) use ($request) {
                $query->where("lastname", 'LIKE', "%" . $request->get('lastname') . "%");
            })->when($request->has('firstname'), function (Builder $query) use ($request) {
                $query->where("firstname", 'LIKE', "%" . $request->get('firstname') . "%");
            })->when($request->has('email'), function (Builder $query) use ($request) {
                $query->where("email", 'LIKE', "%" . $request->get('email') . "%");
            })->get();
    }

    public function findBy($request){
        return $this->client->with("companies")->where('status' , 1)->when($request->has('name'), function (Builder $query) use ($request) {
             $query->where("firstname", 'LIKE', "%" . $request->get('name') . "%")->orWhere("lastname", 'LIKE', "%" . $request->get('name') . "%");
        })->get();
    }

    public function getById($id){
        return $this->client->where("id", $id)->with("companies")->first();
    }


    public function getAll($request){
        return $this->client->with("companies")->when($request->has('active'), function (Builder $query) use ($request) {
            $query->where('status' ,    (integer) $request->get('active'));
        })->when(!$request->has('active'), function (Builder $query) use ($request) {
            $query->where('status' ,    1);
        })->orderBy("created_at" , "DESC")->get();
    }

    /** Method to create Client
     * @param array $data
     * @return Clients
     */

    public function create(array $data) : Clients{
        return $this->client->create($data);
    }

    public function update($id , $data ){
        $client = $this->getFirst($id);
        $data["user_id"] = auth()->id() ?? 27; // for tests

        $client->update($data);
        return $client;
    }

    public function delete($id){
        return $this->client->where("id" , $id)->delete();
    }

}
