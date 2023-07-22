<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Comment extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create();

        $dataComment = [
            'category_id' =>
            $faker->numberBetween(1, 3),
            'rating_id' =>
            $faker->numberBetween(1, 5),
            'user_id'
            => $faker->numberBetween(1, 5),
            'comment' => $faker->sentence
        ];

        $dataRating = [
            'facility' => $faker->numberBetween(1, 5),
            'food' => $faker->numberBetween(1, 5),
            'happiness' => $faker->numberBetween(1, 5),
        ];

        $username = $faker->userName();
        $dataUser = [
            'username' => $username,
            'email' => $faker->email(),
            'phone_number' => rand(111111111111, 999999999999),
            'otp_code' => null,
            'otp_code_exp' => null,
            'school' => 'SMKN 12 Jakarta',
            'avatar' => "avatars.dicebear.com/api/open-peeps/$username.svg",
            'password' => $faker->password(),
        ];

        for ($i = 0; $i < 100; $i++) {
            $data[$i] = $dataUser;
            // $this->db->table('rating')->insert($dataRating);
            // $this->db->table('user')->insert($dataUser);
            // $this->db->table('comments')->insert($dataComment);
        }
        
    }
}
