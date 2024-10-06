<?php

namespace App\Http\Controllers\Admin\Bills;

use App\Helpers\Helper;
use App\Http\Controllers\Controller;

class SettingsController extends Controller
{
    /** Public route for getting timezone list
     * @return mixed
     */

    public function getTimeZoneList(){
        return Helper::getTimeZoneList();
    }

    /**
     * Route for updating team settings
     */
    public function update(){
        return null;
    }
}
