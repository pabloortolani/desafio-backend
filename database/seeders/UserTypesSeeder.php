<?php

namespace Database\Seeders;

use App\Models\UserTypes;
use Illuminate\Database\Seeder;

class UserTypesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        array_map(function ($name) {
           UserTypes::firstOrCreate([
               'name' => $name
           ]);
        }, config('Seeders.user_types'));
    }
}
