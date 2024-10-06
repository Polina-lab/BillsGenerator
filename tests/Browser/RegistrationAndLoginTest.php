<?php

namespace Tests\Browser;

use Laravel\Dusk\Browser;
use Tests\Browser\Help\LoginTrait;
use Tests\DuskTestCase;
use \Illuminate\Foundation\Testing\WithFaker;

class RegistrationAndLoginTest extends DuskTestCase
{
    use WithFaker;
    use LoginTrait;
    protected $dir = "Test";
    protected $password = "password";
    protected $email = "demo@test.ru";


    public function testLoginAllWay(){
        $this->password = 'password';
        $this->dir = 'LoginFormAllWay';
        $this->checkOrCreateDirectory($this->dir);
        $this->browse(function (Browser $browser) {
            $this->login($browser , ['email' => $this->email , 'password' => $this->password , 'dir' => $this->dir]);
        });
    }

    /** test to check login form
     * @throws \Throwable
     */

    public function testLoginForm(){
        $this->password = 'password';
        $this->dir = 'LoginForm';
        $this->checkOrCreateDirectory($this->dir);
        $this->browse(function (Browser $browser) {
            $this->goToLoginPage($browser);
            $browser->pause(1000);
            $this->loginForm($browser);
            $browser->pause(1000);
            $browser->screenshot( $this->dir . '/assertSeeLoginPage');
            try {
                $browser->assertSee('ackage');
            }catch (\Exception $e){
                $browser->assertSee('aket');
            }
        });
    }

    /** test to check using invalid password
     * @throws \Throwable
     */

    public function testFailedLoginFormPassword(){
        $this->password = 'password909090';
        $this->dir = 'FailedLoginFormPassword';
        $this->checkOrCreateDirectory($this->dir);
        $this->browse(function (Browser $browser) {
            $this->goToLoginPage($browser);
            $browser->pause(1000);
            $this->loginForRegister($browser , ['email' => $this->email , 'password' => $this->password , 'dir' => $this->dir] );
            $browser->pause(1000);
            $browser->screenshot( $this->dir . '/assertSeeLoginPage');
            $browser->assertSee('Wrong email or password');
        });
    }

    /** test to check using invalid email
     * @throws \Throwable
     */

    public function testFailedLoginFormEmail(){
        $this->email = 'vsgauy@shu.ru';
        $this->dir = 'FailedLoginFormEmail';
        $this->checkOrCreateDirectory($this->dir);
        $this->browse(function (Browser $browser) {
            $this->goToLoginPage($browser);
            $browser->pause(1000);
            $this->loginForRegister($browser, ['email' => $this->email , 'password' => $this->password , 'dir' => $this->dir]);
            $browser->pause(1000);
            $browser->screenshot( $this->dir . '/assertSeeLoginPage');
            $browser->assertSee('User not found');
        });
    }

    /** test to check invalid email
     * @throws \Throwable
     */
    public function testInvalidDataWhenCreatingNewAccount(){
        $this->email = $this->faker->text;
        $this->dir = 'InvalidDataWhenCreatingNewAccount';
        $this->checkOrCreateDirectory($this->dir);
        $this->browse(function (Browser $browser) {
            $this->goToRegisterPage($browser);
            $this->registrationForm($browser);
            $browser->pause(1000);
            $browser->screenshot( $this->dir . '/InvalidData');
            $browser->assertSee("Please enter email");
        });
    }

    /** creating new account (test)
     * @throws \Throwable
     */
    public function testCreatingNewAccount()
    {
        $this->email = $this->faker->email;
        $this->password = 'user90';
        $this->dir = 'CreatingNewAccount';
        $this->checkOrCreateDirectory($this->dir);
        $this->browse(function (Browser $browser)  {
            $this->goToRegisterPage($browser);
            $this->registrationForm($browser);
            $browser->pause(3000);
            $this->secondPage($browser);
            $browser->pause(2000);
            $this->loginForRegister($browser, ['email' => $this->email, 'password' => $this->password, 'dir' => $this->dir]);
            $browser->pause(6000);
            $this->fillPersonalData($browser);
            $browser->assertSee('Fill firm data');
            $browser->check('#firm_data_checkbox');
            $browser->assertSee("Firm data");
            $this->addFirmData($browser);
            $this->addBankData($browser);
            $browser->screenshot( $this->dir . '/fillPersonalData_end');
            $browser->click('button.btn.btn-success.m-2');
            $browser->pause(1000);
            $browser->screenshot( $this->dir . '/Choose_package');
            //$browser->assertSee("Choose package");
        });
    }

    /** fill personal data
     * @param $browser
     */

    protected function fillPersonalData($browser){
        $browser->screenshot( $this->dir . '/fillPersonalData');
        $browser->assertSee('Personal info');
        $browser->type("#firstname" , $this->faker->firstName);
        $browser->type("#lastname" ,$this->faker->lastName );
        $browser->select('#language', rand(1, 3));
        $browser->type("#address" , $this->faker->address);
       // $browser->type("#phone" , $this->faker->phoneNumber);
        $browser->type("#phone" , $this->faker->phoneNumber);
        $browser->type("#password",'password' );
        $browser->type("#password2",'password' );
       /* $browser->click('Save');
          $browser->screenshot( $this->dir . '/fillPersonalData_end');
          $browser->storeSource('fillPersonalData_end.html');
       */
    }


    /** add firm data
     * @param $browser
     */
    protected function addFirmData($browser){
        $browser->type('#name' , $this->faker->name);
        $browser->type('#firm_title' , $this->faker->name);
        $browser->type('#reg_num' , $this->faker->iban());
        $browser->type('#vat_number' , $this->faker->bankAccountNumber);
        $browser->type('#firm_address' , $this->faker->address);
    }


    /** add bank data
     * @param $browser
     */

    protected function addBankData($browser){
        $browser->type('#bank_name' , $this->faker->name);
        $browser->type('#bank_address' , $this->faker->address);
        $browser->type('#bank_num' , $this->faker->bankAccountNumber);
        $browser->type('#bank_swift' , $this->faker->swiftBicNumber);
    }


    /** go to register Page
     * @param $browser
     */

    protected function goToRegisterPage($browser){
        $browser->visit('https://bills.gloreal.ee')
            ->assertSee('Bills')
            ->assertSee('Registration');
        $browser->screenshot( $this->dir . '/assertSeeFirstPage');
        $browser->clickLink('Registration');
    }



    /** working with registration form
     * @param $browser
     */
    protected function registrationForm($browser){
        $browser->pause(1000);
        $browser->assertSee('Welcome');
        $browser->screenshot( $this->dir . '/assertSeeWelcome');
        $browser->type('.form-control' , $this->email );
        $browser->clickAtXPath('/html/body/div[1]/div/div/div[1]/div/div/main/div[2]/div/article/div[2]/button');

    }


    /** working with second step
     * @param $browser
     */
    protected function secondPage($browser){
        $browser->screenshot( $this->dir . '/assertSeeSecondPage');
        $browser->assertSee("Thank");
        $browser->assertSee('Login');
        $browser->clickAtXPath('/html/body/div[1]/div/div/div[1]/div/div/main/div/div/article/div[2]/a');
    }






}
