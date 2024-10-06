<?php


namespace App\Helpers;
use App\Models\Bills\Settings;
use Illuminate\Support\Facades\Cache;

class Helper
{
    /** method to getting timezoneList
     * @return mixed
     */

    static public function getTimeZoneList(){
        return Cache::rememberForever('timezones_list_collection', function () {
            $timestamp = time();
            foreach (timezone_identifiers_list(\DateTimeZone::ALL) as $key => $value) {
                date_default_timezone_set($value);
                $timezone[$value] = $value . ' (UTC ' . date('P', $timestamp) . ')';
            }
            return collect($timezone)->sortKeys();
        });
    }

    /**
     * That method working only with local Database
     *
     * @return mixed
     */

    static public function getCurrentTimeZone(){
        return optional(Settings::value('timezone')) ?? config('app.timezone');
    }

}
