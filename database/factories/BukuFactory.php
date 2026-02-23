<?php

namespace Database\Factories;

use App\Models\Kategori;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Buku>
 */
class BukuFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'kode' => strtoupper(fake()->bothify('??-###')), // Contoh: NV-123
            'judul' => fake()->sentence(rand(2, 5)),
            'pengarang' => fake()->name(),
            'idkategori' => Kategori::factory(), // Otomatis buat kategori baru
        ];
    }
}