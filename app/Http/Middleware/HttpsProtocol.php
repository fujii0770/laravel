<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 8/12/2018
 * Time: 9:51 AM
 */

namespace App\Http\Middleware;


class HttpsProtocol
{
    public function handle($request, \Closure $next)

    {

        if (!$request->secure()) {

            return redirect()->secure($request->getRequestUri());

        }


        return $next($request);

    }
}