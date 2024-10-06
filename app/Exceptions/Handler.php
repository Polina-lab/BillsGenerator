<?php

namespace App\Exceptions;

use Throwable;
use Illuminate\Foundation\Exceptions\Handler as ExceptionHandler;

class Handler extends ExceptionHandler
{
    /**
     * A list of the exception types that are not reported.
     *
     * @var array
     */
    protected $dontReport = [
        //
    ];

    /**
     * A list of the inputs that are never flashed for validation exceptions.
     *
     * @var array
     */
    protected $dontFlash = [
        'password',
        'password_confirmation',
    ];

    /**
     * Register the exception handling callbacks for the application.
     *
     * @return void
     */
    public function register()
    {
        //
    }

    /** Method to get url
     * @return string
     */
    protected function getUrl(){
        $res = "";
        if(url()) $res = "<pre><b>URL :</b>". url()->current() ."</pre>";
        return $res;
    }


    /** Method to get user
     * @return string
     */

    protected function getUserId(){

        $res = "";
        if(auth()->id()) $res = "<pre><strong>User ID:</strong>[". auth()->id() ."] ". auth()->user()->email ."</pre>";
        return $res;
    }

    /** Method to get trace
     * @param $e
     * @return string
     */

    protected function getTrace($e){
        return "<pre><b>Trace</b>". $e->getTraceAsString() . "</pre>";
    }

    /** Method to send report in telegram
     * @param Throwable $e
     * @throws Throwable
     */

    public function report( Throwable $e)
    {
        /*if(!empty($e->getMessage())) {
            if($e->getMessage() != "Unauthenticated."){
                file_get_contents("https://api.telegram.org/bot1033910380:AAEU566rBupyCxa40wjEfPXjzzDcZOi8sOc/sendMessage?chat_id=-1001070543129&text=" .
                    $this->getUserId(). $this->getUrl().
                    "<strong>". $e->getMessage() ."</strong>".
                    "<pre><b>Code:</b>". $e->getCode() . "</pre>".
                    "<pre><b>File :</b> " . $e->getFile() . "</pre>".
                    "<pre><b>Line :</b>" . $e->getLine() . "</pre>&parse_mode=html");
            }
        }*/
        parent::report($e);
    }


}
