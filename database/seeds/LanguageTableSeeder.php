<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class LanguageTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $languageCodes = [
            [
                'language_code' => 'en',
                'country_code' => 'UK'
            ],
            [
                'language_code' => 'de',
                'country_code' => 'DE'
            ],
            [
                'language_code' => 'fr',
                'country_code' => 'FR'
            ],
            [
                'language_code' => 'ru',
                'country_code' => 'RU'
            ],
            [
                'language_code' => 'es',
                'country_code' => 'ES'
            ],
            [
                'language_code' => 'pt',
                'country_code' => 'PT'
            ]
        ];
        DB::table('languages')->insert($languageCodes);
    }
}
