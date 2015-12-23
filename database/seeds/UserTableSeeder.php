<?php
/**
* Documentar.
*/
use App\User;
use Faker\Factory as Faker;
use Illuminate\Database\Seeder;

class UsersTableSeeder extends Seeder
{
    public function run()
    {
        $faker = Faker::create();
        #creating some users
        for ($i = 0; $i < 10; $i++) {
            User::create([
                'first_name' => $faker->firstName,
                'last_name'  => $faker->lastName,
                'email'      => $faker->unique()->email,
                'password'   => \Hash::make('123456'),
            ]);
        }
    }
}
