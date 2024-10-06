<?php

namespace App\Traits;

use App\Models\Bills\Users;
trait CheckRole
{

    /** Check role by string
     * @param string $role_str
     * @return bool
     *
     * return true when constants permission in role
     */

    public  function check_role( string $role_str ): bool{
        $resp = true; // need be false by default   TODO:// !
        // that working - but we doesnt have permissions - role relationship
        /*
        if(isset(auth()->user()->local_id)){
            $user = Users::where('id' , auth()->user()->local_id)->first();
            if($user){
                 $resp = $user->roles->permissions()->pluck("sys_name")->contains($role_str);
            }
        }else{
            dd( __CLASS__ ,  auth()->user() );
        }*/

        return $resp;
    }


}
