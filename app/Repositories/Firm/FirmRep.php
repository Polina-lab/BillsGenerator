<?php


namespace App\Repositories\Firm;

use App\Models\Bills\Firms;

class FirmRep
{
    protected $firm;

    public function __construct(Firms $f){
        $this->firm = $f;
    }

    public function getAll(){
        return $this->firm->all();
    }

    public function getById(int $id){
        return $this->firm->where("id" , $id)->first();
    }

    public function findByName(string $name){
        return $this->firm->where('name' , $name)->get();
    }

    public function create(array $data){
        return $this->firm->create($data);
    }

    public function update( int $id , array $data){
        $firm = $this->getById($id);
        return $firm->update($data);
    }

    public function delete(int $id){
        $firm = $this->getById($id);
        return $firm->delete();
    }

}
