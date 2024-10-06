<?php

namespace App\Http\Controllers\Admin\Bills;

use App\Http\Requests\Bills\BillsRequest;
use App\Http\Requests\Bills\CreateGenRequest;
use App\Http\Requests\Bills\CreateRequest;
use App\Http\Requests\Bills\getUniqNumberRequest;
use App\Http\Requests\Bills\UpdateGenRequest;
use App\Http\Requests\Bills\UpdateRequest;
use App\Http\Requests\HasDigitIdRequest;
use App\Http\Resources\ArrayTranducerWData;
use App\Http\Resources\ArrayTransducer;
use App\Http\Resources\Bills\BillResource;
use App\Http\Resources\Bills\getBillResource;
use App\Notifications\MailSender;
use App\Services\Bills\BillsService;
use App\Traits\CheckRole;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class BillsController extends Controller
{
    protected  $bills;
    protected $msg;
    use CheckRole;

    public function __construct(BillsService  $b){
        $this->bills = $b;
        $this->msg = ["msg" => "Email sent" ,"code" => 200 , 'type'=> "success" ];
    }

    /** to sellect  bills
     * @param BillsRequest $request
     * @return BillResource
     */

    public function getAll( BillsRequest  $request){
        return  new BillResource($this->bills->getAllWithRequest($request));
    }

    /** get bills by Id
     * @param HasDigitIdRequest $id
     * @return getBillResource
     */

    public function getById(HasDigitIdRequest $id){
        return new getBillResource($this->bills->getByIdFull($id->id));
    }

    /** method to update time when bill was paid
     * @param HasDigitIdRequest $id
     * @return ArrayTransducer
     */

    public function wasPaid(HasDigitIdRequest $id){
        return new ArrayTransducer($this->bills->changeWasPaid($id->id));
    }

    /** method to get uniq number
     * @param getUniqNumberRequest $request
     * @return array['number' => int]
     */

    public function getUniq(getUniqNumberRequest $request){
        return $this->bills->getUniqNumber($request->all());
    }

    /** method to create bill
     * @param Request $request
     * @return ArrayTransducer
     */

    public function create( CreateRequest $request){
        if(gettype($this->bills->create($request->all())) === 'object' )
            $this->msg = ["msg" => "Bills created" ,"code" => 200 , 'type'=> "success"];
        else
            $this->msg = ["msg" => "Oops: something was wrong.." ,"code" => 500 , 'type'=> "error"];
        return new ArrayTransducer($this->msg);
    }

    /** method to clone bill
     * @param HasDigitIdRequest $id
     * @return ArrayTransducer
     */

    public function clone(HasDigitIdRequest $id){
        return new ArrayTransducer($this->bills->clone($id->id));
    }

    /** method to update bill
     * @param HasDigitIdRequest $id
     * @param UpdateRequest $request
     * @return ArrayTransducer
     */

    public function update(HasDigitIdRequest $id ,UpdateRequest $request){
        return new ArrayTransducer($this->bills->update($id->id , $request->all()));
    }

    /** method to update bill Gen
     * @param HasDigitIdRequest $id
     * @param UpdateGenRequest $request
     *
     * @return mixed
     */

    public function updateGen(HasDigitIdRequest $id , UpdateGenRequest  $request){
        $res = $this->bills->update($id->id,  $request->all());
        if($res["code"] === 200) return new getBillResource($this->bills->getByIdFull($id->id));
        else return new ArrayTranducerWData($res);
    }

    /** method to create new billGen
     * @param CreateGenRequest $request
     * @return mixed
     */

    public function createGen(CreateGenRequest $request){
        $bill = $this->bills->create($request->all());
        return $this->bills->getByIdFull($bill->id);
    }

    public function decode($file){
        $b64file = trim( preg_replace( "/^data:(.*)base64,/", '', $file ) );
        $b64file        = str_replace( ' ', '+', $b64file );
        return base64_decode($b64file);
    }


    /** method to send Mail
     * @param UpdateGenRequest $request
     * @return ArrayTranducerWData
     */

    public function sendMail(Request $request){
        if($request->has("sender_mail")){
            $user =  auth()->user();

            $body =['theme'=> 'bill', 'username' => 'test', 'data' => $this->decode($request->get('file'))];
            if($request->has('attachment')){
                foreach ($request->get('attachment') as  $a  ){
                    $body['attachment'][] = ['type' => $a['type'] ?? [],  'name' => $a['name'] , 'data' => $this->decode($a['data'])];
                }
            }

            if($request->has('text')){
                $body['text'] = $request->get('text');
            }
            try {
                $user->notify(new MailSender( $body ,$request->get("sender_mail")));
            }catch (\Exception $e){
                $this->msg = ["msg" => "Oops :" . $e->getMessage() ,"code" => 500 , 'type'=> "error" ];
            }
        }else{
            $this->msg = ["msg" => "Oops : check email address"  ,"code" => 500 , 'type'=> "error" ];
        }
        return new ArrayTranducerWData($this->msg);
    }

    /** method to delete bill
     * @param HasDigitIdRequest $id
     * @return ArrayTransducer
     */

    public function delete(HasDigitIdRequest $id){
        return  new ArrayTransducer($this->bills->delete($id->id ));
    }

}
