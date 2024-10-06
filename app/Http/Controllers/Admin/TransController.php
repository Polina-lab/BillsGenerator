<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Trans\CreateKeyRequest;
use App\Http\Requests\Trans\HasGroupKeyRequest;
use App\Http\Requests\Trans\UpdateRequest;
use App\Http\Requests\Trans\CreateGroupRequest;
use App\Services\TranslateService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;

class TransController extends Controller
{
    protected $transServ;

    public function __construct(TranslateService $trans){
        $this->transServ = $trans;
    }

    /** Return all Groups !! store  Cache an week
     *
     * @route get(api/trans/groups)
     */

    public function groups()
    {
        return $this->transServ->getGroups();
    }

    /** Return all langs !! RememberForever
     *
     * @route  get(api/langs)
     */

    public function lang()
    {
        return Cache::rememberForever('langs', function() {
            return $this->transServ->getLangs();
        });
    }

    /**
     * Delete translations by key
     * пока так
     * @param $group
     * @param $key
     */

    public function delete(HasGroupKeyRequest $request){
        return $this->transServ->delete($request);
    }


    /**
     * To update by Key and group
     *
     */
    public function update(UpdateRequest $request)
    {
        return $this->transServ->update($request);
    }

    public function export()
    {
        return $this->transServ->export();
    }

    public function export_backend(){
        return $this->transServ->export_backend();
    }

    public function createGroup(CreateGroupRequest $request){
        return $this->transServ->createGroup($request);
    }

    public function createKey(CreateKeyRequest $request)
    {
        return $this->transServ->createKey($request);
    }

    public function search(Request $request)
    {
        return $this->transServ->search($request);
    }

    public function getTable(Request $request)
    {
        return $this->transServ->getTable($request);
    }

}

