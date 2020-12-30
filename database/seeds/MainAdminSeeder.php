<?php

use Illuminate\Database\Seeder;

class MainAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        return \App\User::create([
            'name' => 'Christian Seewald',
            'email' => 'cs@os-cillation.de',
            'email_verified_at' => now(),
            'username' => 'csKonfigurator',
            'password' => \Illuminate\Support\Facades\Hash::make(getenv('MAIN_ADMIN_PASSWORD')),
            'type' => 1,
            'remember_token' => \Illuminate\Support\Str::random(10)
        ]);
    }
}
