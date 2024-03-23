<?php

namespace Database\Seeders;

use App\Models\Mahasiswa as ModelsMahasiswa;
use Illuminate\Database\Seeder;
use Faker\Factory as Faker;

class mahasiswa extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $faker = Faker::create('id_ID');

        for ($i = 0; $i < 10; $i++) {
            ModelsMahasiswa::create([
                'nim' => $faker->unique()->randomNumber(9),
                'nama' => $faker->name,
                'alamat' => $faker->address,
                'tgl_lahir' => $faker->date($format = 'Y-m-d', $max = '2000-01-01'),
                'gender' => $faker->randomElement(['L', 'P']),
                'usia' => $faker->numberBetween($min = 17, $max = 30),
            ]);
        }
    }
}
