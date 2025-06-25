<?php

namespace App\Console\Commands;

use App\Models\Coin;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage; 

class FetchCryptoPrices extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'crypto:fetch-prices';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch cryptocurrency prices and save to a JSON file';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $coins = Coin::all();

        $exchange_info = Http::get('https://api.binance.com/api/v3/exchangeInfo')->json();
        $symbol_meta = collect($exchange_info['symbols'])->keyBy('symbol');

        $prices = [];

        foreach ($coins as $coin) {
            $symbol = $coin->code;

            $response = Http::get('https://api.binance.com/api/v3/ticker/24hr', [
                'symbol' => $symbol
            ]);

            if ($response->successful() && isset($symbol_meta[$symbol])) {
                $data = $response->json();
                $meta = $symbol_meta[$symbol];

                $prices[$symbol] = [
                    'baseAsset' => $meta['baseAsset'],
                    'quoteAsset' => $meta['quoteAsset'],
                    'price' => $data['lastPrice'],
                    'price_change_percent' => $data['priceChangePercent'],
                    'updated_at' => now()->toDateTimeString(),
                ];
            }
        }

        Storage::disk('local')->put(
            'crypto_prices.json',
            json_encode($prices, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE)
        );

        $this->info('Crypto prices updated.');
    }
}
