<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $user = new User;
        $user->save([
            'name' => "Medina",
            'email' => "abraham.medina@gmail.com",
            'password' => "medina14"
        ]);
    }
}
