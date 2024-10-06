<?php

namespace App\Http\Controllers\Admin;
use App\Helpers\Facade\Switcher;
use App\Http\Controllers\Controller;
use App\Http\Requests\Team\UpdatePaymentPlan;
use App\Http\Resources\ArrayTranducerWData;
use App\Models\PaymentPlan;
use App\Notifications\MailSender;
use App\Services\SystemBills;
use Carbon\Carbon;
use App\Models\Users;
use Illuminate\Support\Facades\Cache;

class PaymentController extends Controller
{

    /** this route return all payments plans where visible set true
     * @return ArrayTranducerWData
     */

    public function index(){
       // return Cache::rememberForever('payment_plans', function() {
              return  new ArrayTranducerWData(PaymentPlan::where("visible", true)->get());
         //   });
    }

    public function statistic(){
        return Cache::remember('statistic', 1440 ,function(){
        $created = new Carbon('2021-07-09');
        $now = Carbon::now();
        $difference = ($created->diff($now)->days < 1)
            ? 'today'
            : $created->diffInDays($now);
        return ['users' =>  Users\User::count(),
                'days' => $difference,
                'bills' => 19289501,
            ];
        }
        );
    }

    public function printPdf(){
        $data = [ 'locale' => 'en',
                  'penalty' => '0,5',
                  'clients' => [ 'firstname'=> "firstname",
                                'lastname' =>  'lastname',
                                'email' => 'email',
                                'address' => "address"
                                ],

                  'number'=> 'test_number',
                  'firms' => [
                      'name' =>  true,
                      'company_name' => 'Glo Real Network OÜ',
                      'reg_num' =>  '14118122',
                      'phone' => '+372 555 99 313',
                      'address' => 'Estonia pst 5, kab. 304',
                      'bank'=>[
                      'bank_name'=> 'LHV',
                      'bank_num'=> 'EE187700771002405343',
                      'bank_account' => '',
                      'bank_swift' => 'LHVBEE22',
                      'bank_address' => 'Tartu mnt 2 Tallinn, 10145, Estonia',
                      ],
                      'km' => 20,
                      'logo_blob' => '',
                      'view_in_invoice' => 1
                  ],
            'companies' => [
                            'name' => 'Имя копании боюю',
                            'address' => 'address',
                            'reg_num' => 'reg_num',
                            'phone' => 'phone',
                            'email' => 'email',
            ],
            'orders' => [[
                         'name' => "dkdjk",
                          'desc' => 'dkdl',
            'unit' =>  'Another',
            'unit_custom' => 'djdj',
            'amount'=> 1 ,
            'price'=> 10000
            ]],
            "price_km" =>  '1000.00' ,
            "price_no_km" => '1000.00',
            "price_with_km" => '1200.00',
            'currency' => 'EUR', //by default
            'date' => Carbon::now(),
            'deadline' => Carbon::now()->addDays(3),
            'payment_method' => 'transfer',
            'payment_method_name' => 'transfer',
            'sender_user' => [ 'username' => 'Glo Real Network OÜ' ],
            ];

    return view('bills/index2' , compact('data'));

          //  $data = $generate_pdf->printBills();
          //  $pdf = PDF::loadView("bills.index2" , compact('data') );
        // return $pdf->download('invoice.pdf');
    }

    /** update payment plan
     * @param UpdatePaymentPlan $request
     * request can have send_to = [ 'person' , 'company' ]
     *
     * @return ArrayTranducerWData
     */

    public function update_payment_plan(UpdatePaymentPlan $request){
        $team =  auth()->user()->team()->with('user' , 'langs' , 'payment')->first();
        $current_user = auth()->user();
        if($team){
            // who to issue invoices to
            if(isset($request->send_to) && $request->send_to == 'company')
                $team->update(['payer' => 0]); //for company
            else
                $team->update(['payer' => 1]); //for user

            $pPlan =  PaymentPlan::where('id' , $request->payment_plan_id )->first();
            Switcher::useLocalDB($team['database']);

            $local_user = \App\Models\Bills\Users::where('id', $current_user->local_id)->first();
            $lang = $local_user->langs->sys_name ?? "ee";

            if(isset($request->send_to) && $request->send_to == 'company') {
                $cur_data = \App\Models\Bills\Firms::all();
                if($cur_data) {
                    $client = $cur_data->where('main_firm', 1)->first() ? $cur_data : $cur_data->first();
                }else {
                    return new ArrayTranducerWData( ['code' => 500,
                        'msg' => "Firm data or Personal data need be required",
                        'type' => 'error']);
                }

            }else {
                $hasClient = \App\Models\Clients\Clients::where(['firstname' => $local_user->firstname ,
                    'lastname' => $local_user->lastname ])->first();
                if(!$hasClient) {
                    $client = \App\Models\Clients\Clients::create([
                        'firstname' => $local_user->firstname,
                        'lastname' => $local_user->lastname,
                        'address' => $local_user->address,
                        "id_code" => '',
                        "comment" => '',
                        "lepping" => true,
                        "work_status" => 1,
                        "agent" => false,
                        'status' => false,//not active
                        "phone" => $local_user->phone,
                        "reg_num" => '',
                        "email" => $local_user->email,
                        "show_in_bills" => true
                    ]);
                }else {
                    $client = $hasClient;
                }

            }

            $order  =   \App\Models\Bills\Orders::create(
                [
                    'name' => ucfirst($pPlan->string),
                    'desc' => " package",
                    'unit' => 'month',
                    'unit_price' => $pPlan->price,
                 //   'amount' => 1, // month
                ]
            );

            if($client){
                $generate_pdf =  new SystemBills(
                    $team, //Team
                    [$order->toArray()], //Order
                    $client->toArray(), //'array'
                    $lang  // language (string) 'ee' || 'ru' || 'en'
                );

                $bill = $generate_pdf->generatePDF();
                $generate_pdf->store();
                $body =[
                    'theme'=> 'bill',
                    'username' => 'test',
                    'message' => 'Register ',
                    'data' => $bill->getContent()];

                auth()->user()->notify(new MailSender( $body , $current_user->email));
                Switcher::useDefaultDB();
                $current_balance =  $team->current_balance ;
                $team->update(['payment_plan_id' => $pPlan->id , 'current_balance' => $current_balance - $pPlan->price ] );
                $msg = ['code' => 200 , 'msg' => "Email was sent"  , 'type' => 'success'];
            }else{
                $msg = ['code' => 404 ,
                    'msg' => "Firm data or Personal data need be required", 'type' => 'error'];
            }
        }else{
            $msg = ['code' => 500 , 'msg' => 'Team not found' , 'type' => 'error'  ];
        }

        return new ArrayTranducerWData($msg);
    }

}
