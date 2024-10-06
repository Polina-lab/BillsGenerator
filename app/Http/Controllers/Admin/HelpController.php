<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\Help\FeedbackRequest;
use App\Notifications\MailSender;
use Illuminate\Support\Facades\Notification;

class HelpController extends Controller
{
    public $msg;
    protected $mail = 'rowlinest90@gmail.com';

    public function feedback(FeedbackRequest $request){
        $this->msg = ['code' => 200 , 'msg' => "Feedback was sent" , 'type' => 'success'];
        $body=  array_merge(['theme' => 'feedback'] , $request->all() );
        if(!Notification::route('mail' , $this->mail )->notify(new MailSender( $body , $this->mail)))
            $this->msg = ['code' => 500 , 'msg' => "Feedback not sent" , 'type' => 'error'];

        return $this->msg;
    }

}
