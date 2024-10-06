<?php

use Illuminate\Support\Facades\Route;


Route::group(['prefix' =>  '/api'] ,function () {
    Route::post('/login', 'API\AuthController@login')->name('login');
    Route::post('/register', 'API\AuthController@register')->name('register');
    Route::post('/reset_password', 'API\AuthController@reset_password')->name('reset_password');
    Route::post('/admin/logout', 'API\AuthController@logout')->name('logout');
    Route::get("/payment_plans" , "Admin\PaymentController@index")->name("payment_plans");
    Route::get('/statistic' , "Admin\PaymentController@statistic")->name('statistic');
    Route::post('/feedback', 'Admin\HelpController@feedback')->name('feedback');
});

Route::group(['prefix' => '/api/admin'], function () {
    /* Coupons */
    Route::get('/coupons', 'Admin\CouponsController@index');
    Route::get('/coupon/{code}', 'Admin\CouponsController@getByCode');
    Route::patch("/coupons/{id}", 'Admin\CouponsController@update');
    Route::delete("/coupons/{id}", 'Admin\CouponsController@delete');
    Route::post('/coupons/create', 'Admin\CouponsController@create');
    /*Settings  get TimeZone */
    Route::get('/settings/getTimeZone' , 'Admin\Bills\SettingsController@getTimeZoneList');
});

Route::group(['prefix' => '/api/v1/'], function () {
    /*Get Users*/
    Route::get('/users', 'Admin\RemoteUsersController@index');
    Route::delete('/team', 'Admin\RemoteUsersController@delete_team');//TODO: add this block

});

Route::group(['middleware' =>[ 'auth:sanctum' , "cors"] , 'prefix' => '/api/admin'], function () {
    /*Langs*/
    Route::get('/langs' , 'Admin\TransController@lang');
    /* TODO: test this */
    Route::get('user/{id?}' , "Admin\UserController@getById");
    /*Trans*/
    Route::get('/trans/groups' , 'Admin\TransController@groups');/*to get groups*/
    Route::get('/trans/export', 'Admin\TransController@export');
    Route::get('/trans/export_backend' ,'Admin\TransController@export_backend');
    Route::get('/trans/{group?}', 'Admin\TransController@getTable');/*to get paginated table*/
    Route::post('/trans/key/add', 'Admin\TransController@createKey');
    Route::post('/trans/group/add' , "Admin\TransController@createGroup");
    Route::patch('/trans/key/update' , 'Admin\TransController@update');
    Route::delete('/trans/key/delete', 'Admin\TransController@delete'); // delete by key and

    /*Api*/
    Route::get("/api_list" , "Admin\ApiController@index");
    Route::post("/api_list/create" , "Admin\ApiController@create");
    Route::get("/api_list/reload" , "Admin\ApiController@reload");
    Route::patch("/api_list/{id}" , "Admin\ApiController@update");
    Route::delete("/api_list/{id}", "Admin\ApiController@destroy");
    Route::get("/api_list/{id}" , "Admin\ApiController@show");
    Route::delete("/api_list/req/{id}", "Admin\ApiController@delete_req" );
    Route::delete("/api_list/resp/{id}" , "Admin\ApiController@delete_resp");

    /*Payments plans*/
    Route::post("/payment_plan" , "Admin\PaymentController@update_payment_plan");

    /*UPDATE USER - set Default team*/
    Route::post('/set_default_team', 'Admin\UserController@setDefaultTeam' );
    Route::post('/disable_notifications' , 'Admin\UserController@disable_notifications');

});

Route::group(['middleware' => ['auth:sanctum', "team", "cors"] , 'prefix' => "/api/{token}"] , function () {

    //Route::post('/');
    /*Users*/
    Route::post('/invite-user' , 'Admin\UserController@invite');
    Route::post('/user/create', 'Admin\UserController@create');

    /*Update Team Data*/
    Route::post('/team/update' , 'Admin\UserController@update_team');

    Route::get("/getUserData" , "Admin\UserController@getUserData");
    // Route::get("/test" , "TestController@index");
    Route::post('/user/update/{id}' , 'Admin\UserController@update')->where("id", '[0-9]+');
    Route::post('/user/updatePassword/{id}' , 'Admin\UserController@update_password')->where("id", '[0-9]+');
    Route::get('/user/permissions' , "Admin\PermissionController@get_all");
    Route::get('/user/roles' , 'Admin\UserController@getRoles');
    Route::get('/users' , "Admin\UserController@getAll")->name("getAllUsers");
    Route::get('/users_permissions' , "Admin\UserController@getAllWithPermission");
    Route::get('/user/{id?}' ,"Admin\UserController@getById" )->name("getUser");
    Route::post('/user/{id}', "Admin\UserController@update")->where("id" , "[0-9]+");
    Route::delete('/user/{id}', "Admin\UserController@delete")->where("id" , "[0-9]+");

    /*Bills*/
    Route::get('/bills' , 'Admin\Bills\BillsController@getAll');
    Route::get('/bill/clone/{id}', "Admin\Bills\BillsController@clone")->where("id", '[0-9]+');
    Route::post('/bill/getUniq' , "Admin\Bills\BillsController@getUniq");
    Route::get('/bills/{id}', "Admin\Bills\BillsController@getById")->where("id", '[0-9]+');
    Route::post('/bills/create', "Admin\Bills\BillsController@create");
    Route::post('/bills/update/{id}' , "Admin\Bills\BillsController@update")->where("id", '[0-9]+');
    Route::post('/bills_gen/create', "Admin\Bills\BillsController@createGen");
    Route::patch("/bill_gen/update/{id}", "Admin\Bills\BillsController@updateGen")->where("id", '[0-9]+');//need be patch
    Route::post("/bills_send_mail" , "Admin\Bills\BillsController@sendMail");
    Route::delete('/bills/{id}', "Admin\Bills\BillsController@delete")->where("id", '[0-9]+');

    /*Permissions*/
    Route::get('/permissions' , "Admin\PermissionController@getAll")->name("getAllPermissions");
    Route::get("/permissions/{role_id}" , "Admin\RoleController@getPermissions")->where("id" , "[0-9]+");
    Route::post("/update_permission" , "Admin\RoleController@update_table");

    /*Roles*/
    Route::get('/roles_with_permissions' , "Admin\RoleController@getAllWithPermissions");
    Route::get('/roles' , "Admin\RoleController@getAll")->name("getAllRoles");
    Route::post('/role/new' , "Admin\RoleController@create");
    Route::get('/role_table' , "Admin\RoleController@getTable");
    Route::patch("/role/{id}" , "Admin\RoleController@update")->where("id" , "[0-9]+");
    Route::delete("/role/{id}" , "Admin\RoleController@delete")->where("id" , "[0-9]+");

    /*Firms*/
    Route::get('/firms' , "Admin\Bills\FirmsController@getAll");
    Route::post('/firm/create', "Admin\Bills\FirmsController@create");
    Route::post('/firm/update/{id}' , "Admin\Bills\FirmsController@update")->where("id", '[0-9]+');
    Route::delete('/firm/delete/{id}', "Admin\Bills\FirmsController@delete")->where("id", '[0-9]+');
    Route::get('/respresentatives' , "Admin\Bills\FirmsController@getAll_represetatives");
    Route::delete('/firm/deleteBank/{id}', "Admin\Bills\FirmsController@delete_bank")->where('id' , '[0-9]+' );

    /*Orders*/
    Route::get('/orders' , "Admin\Bills\OrderController@getAll");
    Route::get('/order/{id}' , 'Admin\Bills\OrderController@getById')->where("id", '[0-9]+');
    Route::post('/order/create', "Admin\Bills\OrderController@create");
    Route::patch('/order/{id}' , "Admin\Bills\OrderController@update")->where("id", '[0-9]+');
    Route::delete('/order/{id}', 'Admin\Bills\OrderController@delete')->where("id", '[0-9]+');
    Route::get('/order_stat/{id}' , 'Admin\Bills\OrderController@getChartData')->where("id", '[0-9]+');

    /*Company*/
    Route::post("/company/create" , "Admin\CompanyController@create");
    Route::post("/company/update/{id}" , "Admin\CompanyController@update");
    Route::get("/company/{id}" , "Admin\CompanyController@getById")->where("id", '[0-9]+');
    Route::delete("/company/{id}" , "Admin\CompanyController@delete")->where("id", '[0-9]+');


    Route::get('categories/{id?}' ,'Admin\Bills\CategoriesController@index');
    //Route::post('categories/create', 'Admin\Bills\CategoriesController@create');
    Route::post('categories/create', 'Admin\Bills\CategoriesController@create');
    Route::post('categories/rebuild','Admin\Bills\CategoriesController@rebuild');
    Route::post('categories/{id}', 'Admin\Bills\CategoriesController@update')->where("id", '[0-9]+');
    Route::delete('categories/{id}', 'Admin\Bills\CategoriesController@delete')->where("id", '[0-9]+');

    /*Clients*/
    Route::get("/clients/find" , "Admin\ClientsController@findBy");
    Route::get('/clients/getAll' , "Admin\ClientsController@getAll");
    Route::get('/client/{id}', 'Admin\ClientsController@getById')->where("id", '[0-9]+');
    Route::post('/client/update/{id}' , "Admin\ClientsController@update");
    Route::post('/client/create', "Admin\ClientsController@create" );
    Route::delete('/client/delete/{id}' , "Admin\ClientsController@delete")->where("id", '[0-9]+');
    Route::post('/client/deleteList' , "Admin\ClientsController@deleteList");//to delete Clients
    Route::post('/client/change_status' , "Admin\ClientsController@changeStatus");

    Route::patch('/settings/edit', "Admin\Bills\SettingsController@update");


});




