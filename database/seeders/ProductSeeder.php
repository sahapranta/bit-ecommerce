<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class ProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            ['name' => 'Best Smartphone', 'title' => 'High-end smartphone with great features'],
            ['name' => 'Sleek Laptop', 'title' => 'Slim and powerful laptop for work and entertainment'],
            ['name' => 'Comfortable T-Shirt', 'title' => 'Soft and stylish T-Shirt for everyday wear'],
            ['name' => 'Classic Jeans', 'title' => 'High-quality jeans for a timeless look'],
            ['name' => 'Sports Sneakers', 'title' => 'Durable sneakers for sports and outdoor activities'],
            ['name' => 'Complete Skincare Set', 'title' => 'All-in-one skincare set for healthy and glowing skin'],
            ['name' => 'Modern Bookshelf', 'title' => 'Stylish bookshelf to organize your favorite books'],
            ['name' => 'Smart Fitness Tracker', 'title' => 'Track your fitness activities with this smart device'],
            ['name' => 'Elegant Necklace', 'title' => 'Beautiful necklace to complement your outfit'],
            ['name' => 'Portable Car Vacuum Cleaner', 'title' => 'Keep your car clean with this handy vacuum cleaner'],
            ['name' => 'Interactive Cat Toy Set', 'title' => 'Entertain your cat with this set of interactive toys'],
            ['name' => 'Premium Notebook', 'title' => 'High-quality notebook for writing and sketching'],
            ['name' => '100% Organic Coffee', 'title' => 'Enjoy the rich flavor of organic coffee'],
            ['name' => 'Lightweight Baby Stroller', 'title' => 'Convenient stroller for easy traveling with your baby'],
            ['name' => 'Solar LED Garden Lights', 'title' => 'Illuminate your garden with these eco-friendly lights'],
            ['name' => 'Portable Bluetooth Speaker', 'title' => 'Listen to your favorite music on the go'],
            ['name' => 'Non-Slip Yoga Mat', 'title' => 'Comfortable mat for yoga and exercise routines'],
            ['name' => 'High-Resolution Digital Camera', 'title' => 'Capture stunning images with this camera'],
            ['name' => 'Premium Kitchen Knife Set', 'title' => 'Sharp and durable knives for your kitchen'],
            ['name' => 'True Wireless Earbuds', 'title' => 'Enjoy wireless music with these sleek earbuds'],
        ];

        // disable foreign key check
        Schema::disableForeignKeyConstraints();

        // truncate existing records
        \App\Models\Product::truncate();

        // $this->command->getOutput()->writeln("<info>Seeding:</info> Products");
        // show progress bar
        $this->command->getOutput()->progressStart(count($products));

        foreach ($products as $product) {
            \App\Models\Product::factory()->create($product);
            $this->command->getOutput()->progressAdvance();
        }

        $this->command->getOutput()->progressFinish();

        Schema::enableForeignKeyConstraints();
    }
}
