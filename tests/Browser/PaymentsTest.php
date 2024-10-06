<?php


namespace Tests\Browser;
use Laravel\Dusk\Browser;
use Tests\Browser\Help\LoginTrait;
use Tests\DuskTestCase;
use \Illuminate\Foundation\Testing\WithFaker;


class PaymentsTest extends DuskTestCase
{
    use WithFaker;
    use LoginTrait;
    public $dir;
/*
    public function setUp() :void
    {
        //$this->login();
    }*/


    public function testCardPayment(){
        $this->dir = 'CardPayment';
        $this->checkOrCreateDirectory($this->dir);
        $this->browse(function (Browser $browser) {
            $this->login($browser , ['dir' => $this->dir ] );
            $browser->assertSee('aksmise');//Maksmise
        });
    }

    public function testBankPayment(){


    }

    public function testEditUser(){

    }

/*
    public  function testInvoicePayment(){

    }

    public function testInvoicePaymentPerson(){


    }


    public function testInvoicePaymentCompany(){


    }

    public function testUsingCoupons(){


    }*/




}
