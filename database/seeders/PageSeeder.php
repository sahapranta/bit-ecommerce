<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PageSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $pages = [
            [
                'title' => 'About Us',
                'subtitle' => 'Bitcoin Emporium: A Revolutionary Online Shop for Crypto Shoppers',
            ]
        ];

        $names = [
            'Contact Us',
            'Terms and Conditions',
            'Privacy Policy',
            'Refund Policy',
            'Shipping Policy',
            'Refund Policy',
            'Shipping Policy',
            'Cancellation Policy',
            'FAQ',
            'Delivery Information',
            'Payment Methods',
            'Warranty and Returns',
            'Affiliate Program',
        ];

        foreach ($names as $name) {
            $pages[] = [
                'title' => $name,
            ];
        }

        // truncate existing records
        \App\Models\Page::truncate();

        // $this->command->getOutput()->writeln("<info>Seeding:</info> Products");
        // show progress bar
        $this->command->getOutput()->progressStart(count($pages));

        foreach ($pages as $page) {
            \App\Models\Page::factory()->create($page);
            $this->command->getOutput()->progressAdvance();
        }

        $this->command->getOutput()->progressFinish();
    }
}
