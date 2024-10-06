<?php

namespace App\Models\Users;

use App\Models\TempPassword;
//use App\Observers\UserObserver;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasFactory, HasApiTokens,  Notifiable ;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'email',
        'remember_token',
        "email_verified_at",
        'disable_suggestions',
        "has_buyer",
        'has_multi',
        "default_team",
        "last_login",
        "is_enabled" ,
        "local_id" ];
    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
        'password'
    ];

    protected $guarded = ["password"];

    protected $with = [ ];

    protected  $appends = ["username" => "username" ] ;


    public function receivesBroadcastNotificationsOn()
    {
        return 'user_notifications_'. $this->id .".notif";
    }

    public function getUsernameAttribute(){
        return isset($this->firstname) ? $this->firstname .' '. $this->lastname : "" ;
    }

   /* public static function boot()
    {
        parent::boot();
        //User::observe(UserObserver::class);
    }*/

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function team(){
        return $this->belongsToMany(Team::class , 'pivot_team_users');
    }

    public function password(){
        return $this->hasOne(TempPassword::class  , 'user_id'  );
    }

    /** scope to get User Data from Local database
     * @return mixed
     */
    public function scopeLocalData(){
         return $this->where("id" , $this->local_id);
    }

    /**
     * The channels the user receives notification broadcasts on.
     *
     * @return string
     */


    /** Roles
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function roles(){
        return $this->belongsTo('\App\Models\Users\Role' , 'role' );
    }

    /** assignRole
     * @param Roles $role
     * @return mixed
     */

    public function assignRole(Roles $role)
    {
        return $this->roles()->save($role);
    }

    public function hasRole($role)
    {
        if (is_string($role)) {
            return $this->roles->contains('name', $role);
        }
        return !! $role->intersect($this->roles)->count();
    }

}
