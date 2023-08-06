<?php

namespace Database\Seeders;

use App\Models\Category;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class CategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            ['name' => 'Electronics'],
            ['name' => 'Clothing'],
            ['name' => 'Home & Kitchen'],
            ['name' => 'Beauty & Personal Care'],
            ['name' => 'Books'],
            ['name' => 'Toys & Games'],
            ['name' => 'Sports & Outdoors'],
            ['name' => 'Health & Wellness'],
            ['name' => 'Jewelry'],
            ['name' => 'Automotive'],
            ['name' => 'Pets'],
            ['name' => 'Office & Stationery'],
            ['name' => 'Food & Beverages'],
            ['name' => 'Baby & Kids'],
            ['name' => 'Garden & Outdoor'],
        ];

        // disable foreign key check
        Schema::disableForeignKeyConstraints();
        Category::truncate();

        // $this->command->getOutput()->writeln("<info>Seeding:</info> Categories");
        // show progress bar
        $this->command->getOutput()->progressStart(count($categories));

        foreach ($categories as $category) {
            Category::create($category);
            $this->command->getOutput()->progressAdvance();
        }

        $this->command->getOutput()->progressFinish();

        Schema::enableForeignKeyConstraints();
    }
}
