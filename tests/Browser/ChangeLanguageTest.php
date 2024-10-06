<?php


namespace Tests\Browser;

use Tests\DuskTestCase;
use Laravel\Dusk\Browser;

class ChangeLanguageTest extends DuskTestCase
{

    protected $dir; // переменная - в которой храним путь для screenshots

    protected $available_languages =  [
        [   'name' => 'RUS',
            'value' => 'Авторизация'
        ],[
            'name'  => 'EST',
            'value' => 'Logi sisse'
        ],[
            'name' => 'ENG',
            'value' => 'Login'
        ]
    ];

    public function testChangeLanguage(){
        $this->browse(function (Browser $browser) {
            $browser->visit('https://bills.gloreal.ee')
                ->assertSee('Bills')
                ->assertSee('Login');
            $browser->assertSee('ENG'); // set for default
            $this->dir = 'ChangeLanguage';
            $this->checkOrCreateDirectory($this->dir);
            foreach($this->available_languages as $lang){
                $browser->click('.dropdown-block');
                if($browser->assertSee($lang['name'] )){
                    $browser->clickLink($lang['name']);
                    $browser->pause(1000);
                    $browser->assertSee($lang['value']);
                    $browser->screenshot( $this->dir .'/' . $lang['name'] . '_SELECT');
                }
            }
        });
    }

    public function testCheckSelectedUserLanguage(){
        $this->browse( function (Browser $browser){
            $browser->visit('https://bills.gloreal.ee')
                ->assertSee('Bills')
                ->assertSee('Login');
            $browser->assertSee('ENG'); // set for default
            $this->dir = 'CheckSelectedUserLanguage';
            $this->checkOrCreateDirectory($this->dir);
            $browser->click('.dropdown-block');
            $browser->pause(1000);
            $browser->assertSee($this->available_languages[0]['name']);
            $browser->clickLink($this->available_languages[0]['name']);
            $browser->pause(1000);
            $browser->screenshot($this->dir .'/'. $this->available_languages[1]['name'] ."_test" );
            $browser->assertSee($this->available_languages[0]['value']);
            $browser->clickLink($this->available_languages[0]['value']);
            $browser->pause(1000);
            $browser->assertSee($this->available_languages[0]['value']);
        });

    }



}
