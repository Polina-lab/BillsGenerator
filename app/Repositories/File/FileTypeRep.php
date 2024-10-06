<?php

namespace App\Repositories\File;

class FileTypeRep
{
    protected $file_type;

    /**
     * Для описания типа файлов создавалась таблица в базе - но по факту там одна запись
     * непонятно , зачем это.
     * FileTypeRep constructor.
     */


    public function __construct()
    {
        $this->file_type = collect(['id' => '1' ,'name' => "Images"]);
    }

    public function getAll(){
        return $this->file_type->all();
    }

    public function getById($id){
        return $this->file_type->where('id' , $id)->first();
    }

}
