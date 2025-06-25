<?php

namespace App\Services;

class CountryList
{
   
    public static function getCountries(string $lang = 'en'): array
    {
        $file = base_path("vendor/umpirsky/country-list/data/{$lang}/country.php");

        if (file_exists($file)) {
            return require $file;
        }

        return [];
    }
}
