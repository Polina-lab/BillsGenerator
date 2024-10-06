<?php

namespace App\Notifications;

use App\Mail\FeedbackMessage;
use App\Mail\InviteMessage;
use App\Mail\InvoiceBillSent;
use App\Mail\RegisterMessage;
use App\Mail\RestoreMessage;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class MailSender extends Notification
{
    use Queueable;
    protected $data;
    protected $username;
    protected $mail;

    /**
     * array contains [ sender_mail , data , text , attachment[*][name] , attachment[*][data] ]
     * mail contains email address
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct( array $data , $mail )
    {
        $this->msg = ["code" => 200  , "msg" => "mail sent" , "type" => "success"];
        $this->data = $data;
        $this->mail = $mail;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        switch($this->data["theme"]){
            case 'register' :
                $res  =(new RegisterMessage([ 'theme'=> 'register', 'lang' => 'en', 'mail' => $this->mail ,"password" => $this->data["password"]]))->to($this->mail, 'I-Gen');
                break;
            case 'invite' :
                $res  =(new InviteMessage([ 'theme'=> 'invite', 'lang' => 'en',  'password' => $this->data["password"] ?? null , 'mail' => $this->mail]))->to($this->mail, 'I-Gen');
                break;
            case 'restore':
                $res = (new RestoreMessage([ 'theme' => 'restore','lang' => 'en',  "password" => $this->data["password"] , "mail" => $this->mail]))->to($this->mail, 'I-Gen');
                break;
            case "bill":
                $res =  (new InvoiceBillSent($this->data['username'] ,
                ['theme'=> 'register', 'lang' => 'en', 'mail' => $this->mail , 'message' => $this->data['message'] ?? "" ,
                    'firm_name' => $this->data['firm_name'] ?? $this->data['client_name'] ??  ""]))
                    ->to($this->mail, 'Gloreal')
                    ->attachData($this->data['data'] , 'invoice.pdf', ['mime' => 'application/pdf'] );
                if(isset($this->data["attachment"])) {
                    foreach ($this->data["attachment"] as $file) {
                        $res->attachData($file['data'], $file['name'], ['mime' => $file['type']]);
                    }
                }
                break;
            case 'feedback':
                $res = (new FeedbackMessage([ 'theme' => 'feedback','lang' => 'en', 'name' => $this->data['name'], 'text'=> $this->data['text'] , "email" => $this->data['email']
                ]))->to($this->mail, 'I-Gen');
                break;
            default :
                $res  =(new RegisterMessage([ 'theme'=> 'register' , 'mail' => $this->mail , "password" => $this->data["password"]]))->to($this->mail, 'I-Gen');
                break;
        }

        return  $res;
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [

        ];
    }
}
