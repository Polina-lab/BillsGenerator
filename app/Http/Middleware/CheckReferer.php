<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckReferer
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        if ($request->server("HTTP_REFERER") !== "http://localhost:3000/" || $request->server("HTTP_REFERER") !== "https://admin.gloreal.ee/") {
            return ['code' => 401 ,"msg" => "Oops:" ];
        }
    }
}
