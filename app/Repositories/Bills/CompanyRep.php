<?php


namespace App\Repositories\Bills;


use App\Models\Clients\Company;
use Illuminate\Database\Eloquent\Builder;

class CompanyRep
{
    public $company ;
    public function __construct(Company $c){
        $this->company = $c;
    }

    /** method to get company by id
     * @param int $id
     * @return Company|Builder|\Illuminate\Database\Eloquent\Model|object|null
     */

    public function getById(int $id){
        return $this->company->with("clients")->where("id" , $id)->first();
    }

    /** return count company by Name
     * @param string $name
     * @return integer
     */

    public function getByName(string $name){
        return $this->company->where("name" , $name)->count();
    }


    /** method to find company by name
     * @param $request
     * @return mixed
     */

    public function findBy($request){
            return $this->company->with("clients")->when($request->has('name'), function (Builder $query) use ($request) {
                $query->where("name",'LIKE', "%". $request->get('name') ."%");
            })->get();
    }

    /** Function to getting companies without clients
     * @return mixed
     */

    public function getCompaniesWithoutClient(){
        return $this->company->where("client_id" , null)->orderBy("created_at", "DESC")->get();
    }

    /**
     * @param array $data
     * @return mixed
     */

    public function create(array $data){
    /*    if(!isset($data["user_id"] ) && $data["user_id"] == null ){
            $data["user_id"] = auth()->id(); //TODO: to  check
        }*/

        return $this->company->create($data);
    }

    /**
     * @param array $data
     * @return mixed
     */

    public function updateOrCreate(array $data) {
        $res = false;
        if(isset($data["id"])){
            if( $data["name"]!= null) {
                $res = $this->update($data);// $this->update($data);
            }
        }else{
            if( isset($data["name"])) {
                $res = $this->create($data);
            }
        }
        return $res;
    }

    /**
     * @param array $data
     * @return mixed
     */

    public function update(array $data){
            return $this->company->where("id", $data["id"])->update([
                   "address" => $data["address"],
                    "client_id" => $data["client_id"],
                    "comment" => $data["comment"] ?? "",
                    "email" => $data["email"],
                    "extra" => $data["extra"] ?? "",
                    "lepping" => boolval($data["lepping"]),
                    "name" => $data["name"],
                    "phone" => $data["phone"],
                    "reg_num" => $data["reg_num"],
                    "status" => $data["status"] ?? null,
                    "user_id" => $data["user_id"] ?? auth()->id(),
            ]);
    }

    /**
     * @param int $id
     * @return mixed
     */

    public function delete(int $id){
        return $this->company->where("id" , $id)->delete();
    }

}
