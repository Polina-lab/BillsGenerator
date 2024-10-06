<?php


namespace App\Repositories\Trans;
use App\Models\Langs;

class LangRep
{
    protected $lang;

    public function __construct(Langs $lang)
    {
        $this->lang = $lang;
    }

    public function getAll(){
        return $this->lang->all();
    }

    public function getEnabled(){
        return $this->lang->where("is_enabled", 0)->get([ "id" ,"name" , "sys_name"]);
    }


    public function getAllNameToArray(){
        return $this->lang->get(['id', 'name'])->toArray();
    }

    public function getAllSysName()
    {
        return $this->lang->where('is_enabled', '0')->pluck("sys_name");
    }



}
