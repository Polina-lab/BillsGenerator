<?php

namespace App\Services;

use App\Http\Requests\Trans\UpdateRequest;
use App\Repositories\Trans\LangRep;
use App\Repositories\Trans\TransRep;
use Illuminate\Pagination\LengthAwarePaginator;
use Exception;

class TranslateService
{
    protected $lang;
    protected $transR;
    protected $msg;


    public function __construct(TransRep $t  , LangRep $l ){
        $this->transR = $t;
        $this->lang = $l;
        $this->msg = ["msg" => "Exporting done", "code"=> 200 , "type" => "success" ];
    }

    public function getGroups(){
        return $this->transR->getAllGroups();
    }


    public function getLangs(){
        return $this->lang->getEnabled();
    }

    /* @param "group"
     * @param "key"
     *
     * @return array
     */


    public function delete($request){
        try{
            $this->transR->delete($request);
            $res = [ 'title' => 'Translation','msg' => "ok", 'status' => 'success'];
        }catch ( Exception $e){
            $res = ['title' => 'Translation', 'msg' => $e->getMessage(), 'status' => 'error'];
        }

        return $res;
    }

    protected function structDataNew($data){
        $res= [];
        foreach ($data as $d){

            $all_trans = $this->transR->getByGroupKey($d->group , $d->key);
            foreach ($this->lang->getEnabled() as $l) {
                $langs[$l->sys_name] = $all_trans->where("locale", $l->sys_name)->first()->value ?? null;

            }
            $res[] = array_merge(["group"=> $d->group, "key" => $d->key] , $langs);
        }

        return $res;
    }



    /* @param "group" string
     *
     * @return array
     */

    public function export(){
        $res = [];
        $groups= $this->transR->getAllGroups();

        try {
            foreach ($this->getLangs() as $lang) {
                foreach ($groups as $group) {
                    $res[$group] = [];
                    $keys = $this->transR->getUniqKeyByGroup($group);
                    $data = [];
                    foreach ($keys as $key) {
                        $data[$key->key] = $key[$lang->sys_name] ;
                    }
                    $res[$group] = $data;
                }
                file_put_contents(  "/var/www/bill-generator/resources/nuxt/locales/" . $lang->sys_name . ".json", json_encode($res));
                file_put_contents(  "/var/www/bill-generator/public/locales/" . $lang->sys_name . ".json", json_encode($res));

                file_get_contents('http://runner:UcYs53hNqvZipDz@167.86.126.172:8080/job/nuxt-rebuild/build?token=RENEW_TRANSLATE');
            }
        }catch (\Exception $exception){
            $this->msg = ["code"=> 500 , "msg" => $exception->getMessage() , "type" => "error" ];
        }
        return $this->msg;
    }

    /** To renew local files
     *  @return array
     */

    public function export_backend(){
        $groups=   $this->transR->getAllGroups();
        $langs = $this->lang->getAllSysName();

        try {
            foreach ($groups as $group) {
                foreach ($langs as $lang) {
                    $str = "<?php \n\n\treturn [\n";
                    $values = $this->transR->getKeyVal($group, $lang);
                    foreach ($values as $val) {
                          $v1 = preg_replace('/(\{)/s',":", $val[$lang] );
                          $value = preg_replace('/(\})/s',"", $v1 );
                          $str .= "\t\t" .'"'. $val->key . '" => "' . $value . '",'."\n";
                    }
                    $str .= "\t];";

                    file_put_contents(base_path() . '/resources/lang/' . $lang . '/' . $group . '.php', $str);
                }
            }
        }catch (\Exception $exception){
            $this->msg = ["code"=> 500 , "msg" => $exception->getMessage() , "type" => "error" ];
        }
        return $this->msg;
    }


    /** To create Group
     * @param $request
     *
     * @return array
     */
    public function createGroup($request){
        return $this->transR->createGroup($request);
    }



    /* @param "group" string
     * @param "key" string
     * @param "page" int
     * @param "per_page" int
     *
     * @return array
     */

    public function getTable($request){
        return $this->transR->getAll($request);
    }


    protected function find($name){
        $transByValue = $this->transR->findByValue($name);
        $transByKey =$this->transR->findByKey($name);
        return $transByValue->merge($transByKey);

    }


    /* @param "name"
     *
     * @return array
     */

    public function search($request)
    {
        $current_page = LengthAwarePaginator::resolveCurrentPage();
        $trans = $this->find($request->name);
        $total = $trans->count();
        if($total == 0 ){
            return ['code' => "404" , "msg" => "Not found" , "status" => "error" ];
        }else {
            $translations = $this->structDataNew($trans);
            return $this->paginate($translations , $total ,$current_page );
        }
    }


    /* @param "group" (string)
     * @param "key" (string)
     *
     * @return array
     */

    public function createKey($request){

        $check = $this->transR->hasKey($request);
        if ($check === 0) {
            $this->transR->createKey($request);
            $resp = ['code' => 200 , 'msg' => 'OK', 'status' => 'success' , ];
        } else
            $resp = [ 'code' => 500 ,'msg' => "DOUBLE", 'status' => 'error'];

        return $resp;
    }


    /* @param "lang" string
     * @param "group" string
     * @param "key" string
     * @param "value" string
     *
     * @return array
     */

    public function update(UpdateRequest $request){
        $this->transR->updateByKeyGroup($request);
        return  ['msg' => trans('Notifications.UPDATED'), 'status' => 'success'];
    }

}

