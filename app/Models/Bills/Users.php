<?php

namespace App\Models\Bills;

use App\Models\Bills\Role;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    use HasFactory;

    protected $table = "users";
    protected $fillable = [
        'firstname', 'lastname',
        'address', 'avatar',
        'email', "phone",
        'sex', 'age',
        'birthDay', 'has_contract',
        'langs_id', 'roles_id',
        'email_verified_at',
        'status',
        'is_enabled', /*boolean param */
        'has_multi', 'password'
    ];

    protected $guarded = ["password"];

    protected $hidden = [
        "email_verified_at", 'password'
    ];

    protected $with = [ ];

    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected  $appends = ["username" => "username" ] ;


    public function receivesBroadcastNotificationsOn()
    {
        return 'user_notifications_'. $this->id .".notif";
    }

    public function getUsernameAttribute(){
        return $this->firstname .' '. $this->lastname;
    }

    public function roles(){
        return $this->belongsTo(Role::class , 'roles_id');
    }

    public function locale(){
        return  $this->belongsTo(\App\Models\Langs::class, 'local_id');
    }

    public function bills(){
        return $this->hasMany(Bills::class , 'user_id');
    }

    public function bills_act(){
        return $this->hasMany(Bills::class , 'act_user_id');
    }


    public function langs(){
        return $this->belongsTo(\App\Models\Bills\Langs::class , 'langs_id' );
    }
/* that not needed
    public function permissions(){
        return $this->belongsToMany('\App\Models\Bills\Permission' , 'user_permissions' , 'user_id' , 'permission_id');
    }
*/
}
