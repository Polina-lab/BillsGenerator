<?php

use Illuminate\Support\Facades\Route;
Route::get('bill/test' , "Admin\PaymentController@printPdf" );
Route::get('mail/register' , function(){
    return view('mails/register' , ['data' => [ 'theme' => 'register','lang' => 'en' ,'mail' => 'demo@test.ru' , 'password' => "TEST" ]]);});
Route::get('mail/download' , function(){
    return view('mails/download' , ['data' => ['theme' => 'download' ,  'message' => "<p>This is test message</p> <br/> <p>Hello  ...</p>", 'lang' =>'ru' ,'mail' => 'demo@test.ru' ,'username' => 'Test user', 'password' => "TEST" , 'firm_name' => "Lom Motors" ]]);});
Route::get('mail/restore' , function(){
    return view('mails/restore' , ['data' => ['theme' =>  'restore',  'lang' => 'en' ,'mail' => 'demo@test.ru' , 'password' => "TEST" ]]);});


