<?php

namespace Database\Seeders;

use App\Models\Type;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class TypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        (new Type())->insert([
            [
                // 'id' => 1,
                'name' => 'Shops',
                'slug' => Str::slug('Shops'),
                'parent_id' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                // 'id' => 2,
                'name' => 'Hotels',
                'slug' => Str::slug('Hotels'),
                'parent_id' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                // 'id' => 3,
                'name' => 'Suits',
                'slug' => Str::slug('Suits'),
                'parent_id' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                // 'id' => 4,
                'name' => 'Offices',
                'slug' => Str::slug('Offices'),
                'parent_id' => 0,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                // 'id' => 5,
                'name' => 'Executive Suits',
                'slug' => Str::slug('Executive Suits'),
                'parent_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                // 'id' => 6,
                'name' => 'Presidentail Suits',
                'slug' => Str::slug('Presidentail Suits'),
                'parent_id' => 3,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                // 'id' => 7,
                'name' => 'King Presidentail Suits',
                'slug' => Str::slug('King Presidentail Suits'),
                'parent_id' => 6,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
