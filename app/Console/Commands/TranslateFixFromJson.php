<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Repositories\Trans\TransRep;

class TranslateFixFromJson extends Command
{

    public  $trans ;



    /**
     * The name and signature of the console command.
     *
     * @var string
     */

    protected $signature = 'trans:fromJson';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'This Commands get translations from json files and put in database';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct( TransRep  $t)
    {
        $this->trans = $t;
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $ee =  file_get_contents('storage/langs/ee.json');
        $en =  file_get_contents('storage/langs/en.json');
        $ru =  file_get_contents('storage/langs/ru.json');
        $arr_ee = json_decode($ee , true);
        $arr_en = json_decode($en , true);
        $arr_ru = json_decode($ru , true);

        $arr_langs = ['ee' => $arr_ee , 'en' => $arr_en , 'ru' => $arr_ru];
        foreach ($arr_langs as $lang  => $arr) {
            foreach ($arr as $group_name => $group) {
                foreach ($group as $key => $gr) {

                    $res = $this->trans->getByGroupKey($group_name, $key);

                    if ($res->count() == 0) {
                        $this->info('CREATE group : ' . $group_name . ' key : ' .$key . ' lang : ' . $lang .' value : '. $gr  );
                        $this->trans->createKey(['group' => $group_name, 'key' => $key,
                            'values' => array_merge(['ee' => '', 'ru' => '', 'en' => ''], [$lang => $gr])]);
                    }else {
                        $this->info('UPDATE group : ' . $group_name . ' key : ' . $key . ' lang : ' . $lang . ' value : ' . $gr);
                        $res->first()->update([$lang => $gr]);
                    }

                }
            }
        }

        return 0;
    }
}
