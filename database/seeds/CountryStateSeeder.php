<?php

use App\Country;
use App\State;
use Illuminate\Database\Seeder;

class CountryStateSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $country_list = storage_path('json').'/countries.json';

        try {
            $countries = json_decode(file_get_contents($country_list));
            foreach ($countries as $country){
                Country::create([
                    'id' => $country->id,
                    'name' => $country->name,
                    'iso2' => $country->iso2,
                    'iso3' => $country->iso3,
                    'phone_code' => str_replace(str_replace($country->phone_code, '+', ''), '-', ''),
                    'capital' => $country->capital,
                    'currency' => $country->currency,
                    'currency_symbol' => $country->currency_symbol,
                    'slug' => str_replace(' ', '-', $country->name),
                ]);
            }

            $states_json = storage_path('json').'/states.json';
            $states = json_decode(file_get_contents($states_json));
            foreach ($states as $state){
                State::create([
                    'name' => $state->name,
                    'state_code' => $state->state_code,
                    'country_id' => $state->country_id,
                ]);
            }
        }
        catch (\Exception $e){
            error_log($e->getMessage());
        }
    }
}
