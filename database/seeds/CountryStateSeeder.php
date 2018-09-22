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
                    'code' => $country->sortname,
                    'slug' => str_replace(' ', '-', $country->name),
                ]);
            }

            $states_json = storage_path('json').'/states.json';
            $states = json_decode(file_get_contents($states_json));
            foreach ($states as $state){
                State::create([
                    'country_id' => $state->country_id,
                    'name' => $state->name,
                ]);
            }
        }
        catch (\Exception $e){
            error_log($e->getMessage());
        }
    }
}
