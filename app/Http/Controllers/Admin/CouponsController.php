<?php

namespace App\Http\Controllers\Admin;

use App\Http\Requests\Coupons\CreateRequest;
use App\Http\Requests\Coupons\UpdateRequest;
use App\Http\Requests\HasDigitIdRequest;
use App\Http\Resources\ArrayTranducerWData;
use App\Repositories\CouponsRep;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class CouponsController extends Controller
{
    public  $coupons;

    public function __construct(CouponsRep  $c){
        $this->coupons = $c;
    }

    /** to get all coupons
     * @param Request $request
     * @return ArrayTranducerWData
     */

    public function index(Request  $request): ArrayTranducerWData{
        return new ArrayTranducerWData($this->coupons->getAll($request));
    }

    /** route to get coupon  by Code
     * @param HasDigitIdRequest $id
     * @return ArrayTranducerWData
     */

    public function getByCode($code){
        return new ArrayTranducerWData($this->coupons->getByCode($code));
    }

    /** to create coupon
     * @param CreateRequest $request
     * @return ArrayTranducerWData
     */
    public function create(CreateRequest $request): ArrayTranducerWData{
        return new ArrayTranducerWData( $this->coupons->create($request->all()));
    }

    /** to update coupon
     * @param int $id
     * @param UpdateRequest $request
     * @return ArrayTranducerWData
     */

    public function update(HasDigitIdRequest $id , UpdateRequest  $request): ArrayTranducerWData{
        return new ArrayTranducerWData($this->coupons->update($id->id , $request->all()));
    }

    /** to delete coupon
     * @param HasDigitIdRequest $request
     * @return ArrayTranducerWData
     */

    public function delete(HasDigitIdRequest  $request) : ArrayTranducerWData{
        return new ArrayTranducerWData($this->coupons->delete((int) $request->id));
    }

}
