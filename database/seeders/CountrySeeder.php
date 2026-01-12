<?php

namespace Database\Seeders;

use App\Models\Country;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use League\ISO3166\ISO3166;

class CountrySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $data = (new ISO3166())->all();

        foreach ($data as $country) {
            Country::firstOrCreate([
                'name' => $country['name'],
                'iso_code' => $country['alpha2'],
            ]);
        }
    }
}
