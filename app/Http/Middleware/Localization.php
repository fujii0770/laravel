<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 1/11/2019
 * Time: 10:05 AM
 */
namespace App\Http\Middleware;
use Closure;
use App;
class Localization
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (session()->has('locale')) {
            App::setLocale(session()->get('locale'));
        }
        return $next($request);
    }
}