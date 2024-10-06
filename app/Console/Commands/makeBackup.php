<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use App\Repositories\Team\TeamRep;

class makeBackup extends Command
{
    public $team;
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'backup:database';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command to make database backup';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(TeamRep  $t)
    {
        $this->team  = $t;
        parent::__construct();
    }

    protected function checkFile($filename){
        if (file_exists($filename)) {
            exec("mkdir  /home/alice/backups/bills");
        }
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        $teams = $this->team->getAll();

//        exec("mysqldump -ualice -puser90 bills > /home/alice/backups/bills/$(date +%F)_bills.sql");

        //$this->checkFile(date("Y-m-d"));
        foreach ($teams as $team){
            exec("mysqldump -ualice -puser90 ". $team->database ." > /home/alice/backups/bills/$(date +%F)_". $team->database .".sql");
        }

        return 0;
    }
}
