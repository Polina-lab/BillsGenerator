<?php


namespace Tests\Browser\Help;


use PHPUnit\Exception;

trait LoginTrait
{
     protected $email_ = 'demo@test.ru';
     protected $password_ = "password";
     protected $dir_ = '';

    /**
     * @param $data
     */

     protected function setData( array $data){
         $this->email_ = $data['email'] ?? 'demo@test.ru';
         $this->password_ = $data['password'] ?? 'password';
         $this->dir_ = $data['dir'] ?? 'Fail';
     }

    /** method for login
     * @param $browser
     * @param array $data
     */

    public function login($browser , array  $data ){
        $this->loginForRegister($browser , $data);
        try {
            $browser->assertSee('Paket'); // can be 'Pakett', 'Package' or 'Choose package'
        }catch(Exception $e){
            $browser->assertSee('Package');
           // $this->info($e->getMessage());

        }
        $browser->clickAtXPath("/html/body/div[1]/div/div/div[1]/div/div/div/div[2]/div/div/div[1]/div[2]/div/div[5]/p/button");

        $browser->screenshot( $this->dir_ . '/assertAfterChoosePackage');
    }

    /** we dont need to see "Choose package" when make registration
     * @param $browser
     * @param $data
     */
    public function loginForRegister($browser , $data){
        $this->setData($data);
        $this->goToLoginPage($browser);
        $browser->pause(1000);
        $this->loginForm($browser);
        $browser->pause(1000);
        $browser->screenshot( $this->dir_ . '/assertSeeLoginPage');
    }

    /** working with login form
     * @param $browser
     */
    protected function loginForm($browser){
        $browser->screenshot( $this->dir_ . '/LoginPage');
        $browser->assertSee("Login");
        $browser->type('#email' , $this->email_ );
        $browser->type('#password' , $this->password_);
        $browser->click('.btn.btn-success.mt-3.mb-3');
        //$browser->clickAtXPath('/html/body/div[1]/div/div/div[1]/div/div/div/button');
    }


    protected function setDefaultLaguage(){



    }


    /** go to login page
     * @param $browser
     */
    protected function goToLoginPage($browser){
        $browser->visit('https://bills.gloreal.ee');
/*        $browser->assertSee('EST'); // set for default
        $this->dir = 'CheckSelectedUserLanguage';
        $this->checkOrCreateDirectory($this->dir);
        $browser->click('.dropdown-block');
        $browser->pause(1000);
        $browser->assertSee($this->available_languages[0]['name']);
        $browser->clickLink($this->available_languages[0]['name']);*/
        try {
            $browser->assertSee('Bills');//->assertSee('Login');
        }catch(\Exception $e) {
            $browser->assertSee('Arved');
        }
        $browser->screenshot( $this->dir_ . '/assertSeeFirstPage');
        $browser->clickLink('Login');
    }




}
