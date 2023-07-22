<?php

namespace App\Database\Seeds;

use CodeIgniter\Database\Seeder;

class Comment extends Seeder
{
    public function run()
    {
        $faker = \Faker\Factory::create();

        $dataComment = [
            'category_id' => $faker->numberBetween(1, 3),
            'rating_id' => $faker->numberBetween(1, 5),
            'user_id' => $faker->numberBetween(1, 5),
            'comment' => $faker->sentence
        ];

        for ($i = 0; $i < 100; $i++) {
            $this->db->table('comments')->insert($dataComment);
        }
    }
}
