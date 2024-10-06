<?php
namespace App\Helpers\Facade;
use Illuminate\Support\Facades\Facade;

class SwitcherFacade extends Facade
{
    public static function getFacadeAccessor()
    {
        return 'switchdb';
    }
}
