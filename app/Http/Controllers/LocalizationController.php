<?php
/**
 * Created by PhpStorm.
 * User: hp
 * Date: 1/11/2019
 * Time: 10:03 AM
 */
namespace App\Http\Controllers;
use App;

class LocalizationController extends Controller
{
    public function index($locale)
    {
        App::setLocale($locale);
        //store the locale in session so that the middleware can register it
        session()->put('locale', $locale);
        return redirect()->back();
    }
}