<?php

namespace App\Http\Controllers\Proc;

use App\Http\Controllers\Controller;
use App\Models\MessageKey;
use App\Models\LanguagePolicy;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\DB;

class LanguageController extends Controller
{

    private array $locale;

    public function __construct()
    {
        $this->locale = $this->getLocale();
    }
   
    public function changeLanguage($locale)
    {
        if (in_array($locale, $this->locale)) {
            return redirect()->back()->withCookie(cookie('app_locale', $locale, 525600));
        }

        return redirect()->back();
    }

    private function getLocale()
    {
        $languages = LanguagePolicy::where('type', 'locale')->first()->content;

        foreach ($languages as $key => $val) {
            $data[] = $val['code'];
        }

        if (!isset($data)) {
            return [];
        }

        return $data;
    }
    
}
