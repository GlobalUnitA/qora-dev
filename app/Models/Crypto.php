<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class Crypto extends Model
{
    public static function getCurrentPrice(string $symbol = null): float
    {
        if (!$symbol) {
            return 1;
        }

        $path = storage_path('app/crypto_prices.json');

        if (!File::exists($path)) {
            return 1;
        }

        $json = json_decode(File::get($path), true);

        return $json[$symbol]['price'] ?? 1;
    }
}