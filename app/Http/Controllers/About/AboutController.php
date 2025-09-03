<?php

namespace App\Http\Controllers\About;


use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Vinkla\Hashids\Facades\Hashids;
use Carbon\Carbon;

class AboutController extends Controller
{
    public function __construct()
    {
        
    }

   
    public function index()
    {
        $app_locale = request()->cookie('app_locale', 'en');

        return view('about.about-' . $app_locale);
    }
}