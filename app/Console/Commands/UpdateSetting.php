<?php

namespace App\Console\Commands;

use App\Models\Setting;
use App\Services\AppSettings;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\File;

class UpdateSetting extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'settings:add {key?} {value?}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create New Settings Key Value Pair From User Input';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $key = $this->argument('key');
        $value = $this->argument('value');

        if (!$key) {
            $key = $this->ask('Enter Settings key:');
        }

        if (!$value) {
            $value = $this->ask('Enter Settings value:');
        }

        $type = $this->choice('Select Settings type:', ['text', 'textarea', 'number', 'email', 'password', 'file', 'image', 'checkbox', 'radio', 'select', 'multiselect', 'key-value'], 0);

        Setting::updateOrCreate(['key' => $key], ['value' => $value, 'type' => $type]);

        $this->info("Settings {$key} has been added.");

        $data = [
            'key' => $key,
            'value' => $value,
            'type' => $type,
        ];

        $jsonFilePath = database_path('settings.json');
        $existingData = File::exists($jsonFilePath) ? File::json($jsonFilePath) : [];
        $existingData[] = $data;
        File::put($jsonFilePath, json_encode($existingData, JSON_PRETTY_PRINT));
        AppSettings::clearCache();
    }
}
