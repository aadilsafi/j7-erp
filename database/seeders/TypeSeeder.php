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
                'site_id' => 1,
                'name' => 'Shops',
                'slug' => Str::slug('Shops'),
                'parent_id' => 0,
                'status' => true,
                'account_added' => true,
                'account_number' => '102020',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                // 'id' => 3,
                'site_id' => 1,
                'name' => 'Hotels Suits',
                'slug' => Str::slug('Hotels Suits'),
                'parent_id' => 0,
                'status' => true,
                'account_added' => true,
                'account_number' => '102021',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                // 'id' => 4,
                'site_id' => 1,
                'name' => 'Offices',
                'slug' => Str::slug('Offices'),
                'parent_id' => 0,
                'status' => true,
                'account_added' => true,
                'account_number' => '102022',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                // 'id' => 5,
                'site_id' => 1,
                'name' => 'Executive Suits',
                'slug' => Str::slug('Executive Suits'),
                'parent_id' => 2,
                'status' => true,
                'account_added' => true,
                'account_number' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                // 'id' => 6,
                'site_id' => 1,
                'name' => 'Presidentail Suits',
                'slug' => Str::slug('Presidentail Suits'),
                'parent_id' => 2,
                'status' => true,
                'account_added' => true,
                'account_number' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                // 'id' => 7,
                'site_id' => 1,
                'name' => 'King Presidentail Suits',
                'slug' => Str::slug('King Presidentail Suits'),
                'parent_id' => 5,
                'status' => true,
                'account_added' => true,
                'account_number' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
