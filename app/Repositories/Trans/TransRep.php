<?php


namespace App\Repositories\Trans;


use App\Models\Translation;

use Illuminate\Database\Eloquent\Builder;
class TransRep
{

    protected $trans;

    public function __construct(Translation $t)
    {
        $this->trans = $t;
    }

    public function getAllGroups(){
        return $this->trans->distinct()->pluck('group')->toArray();
    }

    public function getAll($request){
        return  $this->trans->when($request->has("search") , function (Builder $q) use ($request){
                    $q->where('en', 'like' , $request->search ."%")
                    ->orWhere('ee', 'like' , $request->search ."%")
                    ->orWhere('ru', 'like' , $request->search ."%")
                    ->orWhere('group', 'like' , $request->search ."%");
        })->when($request->has("group") , function (Builder $q) use ($request){
            $q->where('group' , $request->group );
        })->paginate($request->has('per_page') ? $request->get("per_page") : 15 );
    }

    public function getUniqKeysAll(){
        return $this->trans->select('group', 'key')->distinct();
    }

    public function getKey_a_Val( $group , $lang){
        return $this->trans->where('group', $group)->get(['key', $lang]);
    }

    public function getKeyVal($group , $lang){
        return $this->trans->where('group', $group)->get(['key', 'en' , 'ee' , 'ru']);
    }

    public function getUniqKeyByGroup($group){
        return $this->trans->where('group' , $group)->get();
    }

    /**
     * @param   $request || array
     * @return  integer
     */

    public function hasKey($request){
        return $this->trans
            ->where('group' , $request->has('group') ?  $request->group : $request['group'])
            ->where('key' , $request->has('key') ? $request->key : $request["key"] )
            ->count();
    }

    public function getByGroupKeyLang($group , $key , $lang){
        return $this->trans->where(['group'=> $group , "key" => $key , 'locale' => $lang])->get();
    }

    public function getByGroupKey($group , $key){
        return  $this->trans->where(['group'=> $group , "key" => $key])->get();
    }

    public function getByKey($key){
        return $this->trans->where("key" , $key)->get();
    }

    public function findInGroup($group , $name){
        return $this->trans->where("group" , $group)->selectRaw("value LIKE %" . $name . "%")->select('key', 'group')->distinct();
    }

    public function findByValue($name){
        return $this->trans->selectRaw("value LIKE %" . $name . "%")->select('key', 'group')->distinct()->get();
    }

    public function findByKey($name){
        return $this->trans->selectRaw("key LIKE % " . $name . "%" )->select('key', 'group')->distinct()->get();
    }

    public function updateByKeyGroup($request){
        $translation =  $this->trans->firstOrNew([
            'group' => $request->group,
            'key' => $request->key,
        ]);

        $translation[$request->lang] = $request->value;
        $translation->save();

        return $translation;
    }


    public function createGroup($request){
        $msg = [];
        $has =  $this->trans->whereGroup($request->name)->count();
        if($has == 0){
            $this->trans->create([
                "en" => '',
                'ee'=> '',
                'ru' => '',
                "group" => $request->name,
                "key" => "name",
            ]);
            $msg = ['code' => 200 , "msg" => "Ok" , "status" => "success"];
        }else{
            $msg = ['code' => 500 , "msg" => "Oops : that group was created" , "status" => "error"];
        }
        return $msg;
    }



    public function createKey($request){
       $this->trans->create([
                    "group" => $request->group ?? $request['group'],
                    "key" => $request->key ?? $request['key'],
                    "en" => $request->values["en"] ?? $request['values']['en'],
                    "ee" => $request->values["ee"] ?? $request['values']['ee'],
                    "ru" => $request->values["ru"] ?? $request['values']['ru']
                ]);
    }

    public function delete($request){
        $this->trans->where('group' , $request->group )->where('key' , $request->key)->delete();
    }


    public function getChangedGroupsCount($groups){
        return $this->trans->where('group', $groups)->count();
    }

}
