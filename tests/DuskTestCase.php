<?php

namespace Tests;

use Facebook\WebDriver\Chrome\ChromeOptions;
use Facebook\WebDriver\Remote\DesiredCapabilities;
use Facebook\WebDriver\Remote\RemoteWebDriver;
//use Laravel\Dusk\TestCase as BaseTestCase;
use BeyondCode\DuskDashboard\Testing\TestCase as BaseTestCase;

abstract class DuskTestCase extends BaseTestCase

{
    use CreatesApplication;

    /**
     * Prepare for Dusk test execution.
     *
     * @beforeClass
     * @return void
     */
    public static function prepare()
    {
        if (! static::runningInSail()) {
            static::startChromeDriver();
        }
    }

    /**
     * Create the RemoteWebDriver instance.
     *
     * @return \Facebook\WebDriver\Remote\RemoteWebDriver
     */
    protected function driver()
    {
        $options = (new ChromeOptions)->addArguments(collect([
            '--window-size=1920,1080',
        ])->unless($this->hasHeadlessDisabled(), function ($items) {
            return $items->merge([
                '--disable-gpu',
                '--headless',
            ]);
        })->all());

        return RemoteWebDriver::create(
            $_ENV['DUSK_DRIVER_URL'] ?? 'http://selenium:4444/wd/hub',
            DesiredCapabilities::chrome()->setCapability(
                ChromeOptions::CAPABILITY, $options
            )
        );
    }

    /**
     * Determine whether the Dusk command has disabled headless mode.
     *
     * @return bool
     */
    protected function hasHeadlessDisabled()
    {
        return isset($_SERVER['DUSK_HEADLESS_DISABLED']) ||
               isset($_ENV['DUSK_HEADLESS_DISABLED']);
    }

    public function checkOrCreateDirectory($directory){
        $def_path = 'tests/Browser/screenshots/';
        if (!file_exists( $def_path . $directory)) {
            return mkdir( $def_path . $directory , 0777, true,);
        }else return true;
    }

    public function checkOrCreateFile($file){
        $def_path = 'tests/Browser/screenshots/';
        if (!file_exists( $def_path . $file)) {
            return touch( $def_path . $file );
        }else return true;
    }

}
