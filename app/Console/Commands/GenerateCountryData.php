<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\Http;
use App\Models\Config\Countries;

class GenerateCountryData extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-country-data';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $response = Http::get('https://restcountries.com/v3.1/all?fields=name,cca2,continents,timezones,region,subregion,currencies');

        $data = $response->json();

        if ($response->successful()) {
            // Process data
            foreach($data as $oneCountry) {
                $existing = Countries::where('name', $oneCountry['name']['common'])->first();
                if (!$existing) {
                    $country = new Countries();
                    $country->name = $oneCountry['name']['common'];
                    $country->code = $oneCountry['cca2'];
                    $country->continent_name = $oneCountry['continents'][0] ?? '';
                    $country->continent_code = $this->continentCode($oneCountry['continents'][0]);
                    $country->timezone = $oneCountry['timezones'][0] ?? '';
                    $country->region = $oneCountry['region'] ?? '';
                    $country->subregion = $oneCountry['subregion'] ?? '';
                    $country->currency = !empty($oneCountry['currencies']) ? implode(', ', array_keys($oneCountry['currencies'])) : '';
                    $country->save();
                    $this->info('Added country: ' . $country->name);
                } else {
                    $this->info('Country already exists: ' . $existing->name);
                }
            }
        } else {
            $this->error('Failed to fetch data from the API.');
        }
    }

    private function continentCode($continentName)
    {
        $mapping = [
            'Africa' => 'AF',
            'Antarctica' => 'AN',
            'Asia' => 'AS',
            'Europe' => 'EU',
            'North America' => 'NA',
            'Oceania' => 'OC',
            'South America' => 'SA',
        ];

        return $mapping[$continentName] ?? '';
    }
}
