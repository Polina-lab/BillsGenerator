<?php

namespace App\Providers;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;
use Illuminate\Support\ServiceProvider;

class DatabaseChangeServiceProvider extends ServiceProvider
{
    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {

    }

    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot(Request $request)
    {
        /*
       if($request->has("team_key")){
           dd("here");
           $request->get("team_key");
       }
        */

/*
        dd( $request->user()    );
        if($request->getHttpHost() !== 'tenant1.app.com'){
            config(['database.connections.tenant.database' => 'tenant1']);
            DB::purge('tenant');
            DB::reconnect('tenant');
        }
*/
    }
}
