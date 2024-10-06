<?php

namespace App\Models\Bills;

use Illuminate\Database\Eloquent\Model;

class Bills extends Model
{

    protected $fillable = [
        "number",
        "invoice",
        "status" ,
        'vat_id' ,
        'deal',
        "locale" ,
        'paid_cash' ,
        "penalty" ,
        "firm_id",
        'bank_id',
        "act_user_id" ,
        "user_id",
        "company_id",
        "client_id",
        "sender_user_id" ,
        'payment_method',
        "was_paid" ,
        "was_sent",
        "currency",
        "date",
        "deadline" ,
        "prepaid",
        'show_footer',
        'show_requisites',
        'type',
    ];


    /** payment_method :
    {id: 1, name: 'cash'},
    {id: 2, name: 'card'},
    {id: 3, name: 'transfer'}
     */

    /** appends data
     * @var string[]
     */
    protected  $appends = ["name" => "name" , "price" => "price" ] ;

    protected $casts = ['orders.pivot.price' => 'double',
        'orders.pivot.amount' => 'double'
        ];

    /**
     * to get name from orders.name
     * @return \Illuminate\Database\Eloquent\HigherOrderBuilderProxy|mixed
     */

    public function getNameAttribute(){
        return $this->orders()->first()->name ?? "";
    }

    /**
     * to get sum from all orders
     * @return float|int
     */
    public function getPriceAttribute(){

        return number_format($this->getSum(), 2 , "." , '');
    }

    /**
     * @return float|int
     */
    private function getSum(){
        $sum = 0;
        foreach ($this->orders()->get() as $order ){
            $sum += $order->pivot->price;
        }
        return $sum ;
    }


    /** to get active users
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user_act(){
        return $this->belongsTo('\App\Models\Users\User', "act_user_id");
    }

    /** to get sender user
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function sender_user(){
        return $this->belongsTo('\App\Models\Users\User', "sender_user_id");
    }

    /** to get Users
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function users(){
        return $this->belongsTo('\App\Models\Users\User', "user_id");
    }

    /** to get firms
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function  firms(){
        return $this->belongsTo("\App\Models\Bills\Firms" , 'firm_id');
    }

    /** to get clients
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function clients(){
        return $this->belongsTo("\App\Models\Clients\Clients" , "client_id");
    }

    /** to get companies
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */

    public function companies(){
        return $this->belongsTo("\App\Models\Clients\Company" , "company_id");
    }

    /** to get orders
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */

    public  function orders(){
        return $this->belongsToMany(\App\Models\Bills\Orders::class , "pivot_orders_bills" ,  'bills_id' , 'orders_id' )
            ->withPivot(['price', 'amount']);
    }

}
