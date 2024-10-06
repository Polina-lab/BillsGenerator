<?php

namespace App\Models\Users;

use App\Models\Langs;
use App\Models\PaymentPlan;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Str;

class Team extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $fillable = [ "name" ,"database", 'key', 'has_created' , 'payment_plan_id' , 'current_balance',
        "active_until" , 'timezone' , 'currency' , 'payer' ];

    public $timestamps = false;

    public static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            $model->created_at = $model->freshTimestamp();
        });
    }

        public function configure(){
            config([
                "database.connections.teams.database" => "teams",
            ]);
            DB::purge('teams');
            DB::reconnect('teams');

            Schema::connection('teams')->getConnection()->reconnect();
            return $this;
        }

        public function use(){
            app()->forgetInstance("teams");
            app()->instance('teams' , $this);
            return $this;
        }

    public function user()
    {
        return $this->belongsToMany(User::class, 'pivot_team_users');
    }
/*
    public function buyer(){
        return $this->user()->where('has_buyer' , true)->get();
    }
*/

    public function setNameAttribute($value){
        if(!$value){
            $this->attributes["name"] = Str::random(8);
        }
    }

    public function payment(){
        return $this->belongsTo(PaymentPlan::class , 'payment_plan_id');
    }

    public function langs(){
        return $this->belongsTo(Langs::class , 'locale' );
    }

}
