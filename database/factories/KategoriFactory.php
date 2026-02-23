<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Kategori>
 */
class KategoriFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $kategoris = [
            'Novel',
            'Biografi',
            'Komik',
            'Ensiklopedia',
            'Sejarah',
            'Sains',
            'Teknologi',
            'Agama',
            'Filsafat',
            'Psikologi',
            'Ekonomi',
            'Pendidikan',
            'Kesehatan',
            'Kuliner',
            'Seni',
            'Musik',
            'Olahraga',
            'Perjalanan',
            'Motivasi',
            'Fiksi Ilmiah',
        ];

        return [
            'nama_kategori' => fake()->unique()->randomElement($kategoris),
        ];
    }
}