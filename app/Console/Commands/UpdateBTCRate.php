<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Storage;

class UpdateBTCRate extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:btc-rate';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Fetch the latest bitcoin rate from API and update the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        // $this->fetchRate();
        $res = $this->fetchRateFromCoinAPI('BTC', \AppSettings::get('currency_code', 'GBP'));
        $this->saveToStorage($res);
        $this->info('Done!');
    }

    protected function saveToStorage($data)
    {
        Storage::put('btc-rate.json', json_encode($data, JSON_PRETTY_PRINT));
    }


    protected function fetchRate(): void
    {
        // "https://block.io/api/v2/get_current_price/?api_key=env('BLOCKIO_BTC_API')&price_base=GBP";

        $url = "https://block.io/api/v2/get_current_price";

        if (!config('blockio.api_key')) {
            $this->info('Please set BLOCKIO API KEY');
            return;
        }

        $params = [
            // 'api_key' => config('blockio.api_key'),
            'api_key' => env('BLOCKIO_BTC_API'),
            // 'price_base' => \AppSettings::get('currency_code', 'GBP'),
            'price_base' => 'GBP',
        ];

        $this->info('Fetching the latest bitcoin rate from API...');

        $response = Http::acceptJson()->get($url, $params)->json();

        if ($response && $response['status'] === 'success') {
            // store the response in storage as json file
            $prices = data_get($response, 'data.prices', []);
            Storage::put('btc-rate.json', json_encode($prices, JSON_PRETTY_PRINT));

            // strategy 1: get the average of all the prices
            $average = collect($prices)->avg('price');
            $this->info("Average: $average");

            // strategy 2: get the price of the first item
            $first = collect($prices)->first();
            $this->info("First: {$first['price']}");

            // strategy 3: get the price of the last item
            $last = collect($prices)->last();
            $this->info("Last: {$last['price']}");

            // strategy 4: get the price of the item with the highest price
            $max = collect($prices)->max('price');
            $this->info("Max: $max");

            // strategy 5: get the price of the item with the lowest price
            $min = collect($prices)->min('price');
            $this->info("Min: $min");

            // strategy 6: get the price of the item based on exchange name
            $exchange = collect($prices)->where('exchange', 'coinbase')->first();
            $this->info("Coinbase: {$exchange['price']}");
        } else {
            $this->info('Response: ' . json_encode($response));
        }
    }

    // Exchange Rates
    protected function fetchRateFromCoinAPI($from, $to)
    {
        $url = "https://rest.coinapi.io/v1/exchangerate/{$from}/{$to}";

        $response = Http::acceptJson()
            ->withHeaders([
                'X-CoinAPI-Key' => env('COINAPI_KEY'),
            ])
            ->get($url)
            ->json();

        return $response;
    }
}
