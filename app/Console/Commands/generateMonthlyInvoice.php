<?php

namespace App\Console\Commands;

use App\Helpers\Facade\Switcher;
use App\Models\Clients\Clients;
use App\Notifications\MailSender;
use App\Repositories\Team\TeamRep;
use App\Services\SystemBills;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Notification;

class generateMonthlyInvoice extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'bill:generate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Generate monthly invoice payment';

    /**
     * Create a new command instance.
     *
     * @return void
     */

    protected $team;

    public function __construct(TeamRep  $t)
    {
        $this->team = $t;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $teams = $this->team->getAll();

        foreach ($teams as $team){
            //set timezone
            if(!empty($team->timezone)) {
                Config::set('app.timezone', $team->timezone);
            }


            $lang = $team->langs->sys_name ?? 'en';
            if($team->payment !== null &&  Switcher::hasDB($team->database) && $team->user->count() >= 1){
                Switcher::useLocalDB($team->database);
                $orders = [];
                if( $team->current_balance < 0 ) {
                    //включаем задолжность за прошлый месяц
                    $order = \App\Models\Bills\Orders::create(
                        [
                            'name' => ucfirst($team->payment->string),
                            'desc' => " package",
                            'unit' => 'month', // или тут колличество дней
                            'unit_price' => $team->current_balance,
                            'active' => 1,
                        ]
                    );
                    array_push($orders , $order->toArray());
                  }
                    //за будующий месяц
                    $order = \App\Models\Bills\Orders::create(
                        [
                            'name' => ucfirst($team->payment->string),
                            'desc' => " package", //TODO: с какого по какое
                            'unit' => 'month', // или тут колличество дней
                            'unit_price' => $team->payment->price,
                            'active' => 1
                        ]
                    );
                    array_push( $orders , $order->toArray());
                    if($team->payer === 0 ) {
                        $client = \App\Models\Bills\Firms::where('main_firm', 1)->first();//find company
                    }else {
                        $user = $team->user->where('has_buyer' , true)->first();
                        //Client with that email need be created
                        $client = Clients::where('email' , $user->email)->first();
                    }

                    if($client) {
                        $generate_pdf = new SystemBills(
                            $team, //Team
                            [$order->toArray()], // 'array' Orders
                            $client->toArray(), //'array'
                            $lang  // language (string) 'ee' || 'ru' || 'en'
                        );
                        $bill = $generate_pdf->generatePDF();
                        $generate_pdf->store();
                        // send mail
                        $body = [
                            'theme' => 'bill',
                            'username' => 'test',
                            'message' => 'Register ',
                            'data' => $bill->getContent()];

                        Notification::route('mail' , $user->email)->notify(new MailSender($body, $user->email));
                    }

                Switcher::useDefaultDB();
            }// if team_payment !==  null
        }

        return 0;
    }
}
