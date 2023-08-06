<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller
{

    public function index()
    {
        $settings = \AppSettings::asFlatArray();
        $settings = (object) $settings;
        return view('backend.setting.index', compact('settings'));
    }

    public function action(Request $request, $action)
    {
        if (!method_exists($this, $action)) {
            return redirect()->back()->with('error', 'Invalid action.');
        }

        return $this->$action($request);
    }

    public function clearCache()
    {
        \Artisan::call('cache:clear --quiet');
        return redirect()->back()->with('success', 'Cache cleared successfully.');
    }

    public function clearCacheForce()
    {
        \Artisan::call('optimize:clear --quiet');
        return redirect()->back()->with('success', 'Cache optimized successfully.');
    }

    public function homepage(Request $request)
    {
        $requests = $request->except(['_token', '_method']);

        if ($request->hasFile('logo')) {
            $file = $request->file('logo');
            $filename = 'logo.' . $file->getClientOriginalExtension();
            $file->move(public_path('uploads'), $filename);
            $requests['logo'] = $filename;
        }

        // if ($request->site_name != config('app.name')) {
        //     $this->updateConfig('app.name', $request->site_name);
        // }

        foreach ($requests as $key => $value) {
            $this->updateSettings($key, $value);
        }

        \AppSettings::clearCache();

        return back()->with('success', 'Homepage settings updated successfully.');
    }


    protected function updateSettings($key, $value)
    {
        $settings = \AppSettings::all();
        $setting = $settings->where('key', $key)->first();

        if ($setting) {
            $value = match ($setting->type) {
                'checkbox' =>  $value == 'on' ? true : false,
                'key-value' => json_encode($value, JSON_UNESCAPED_UNICODE),
                default => $value,
            };
            if ($setting->value != $value) {
                $setting->update(['value' => $value]);
            }
        }
    }

    protected function updateConfig($key, $value)
    {
        $envFile = app()->environmentFilePath();
        $str = file_get_contents($envFile);

        $oldValue = config($key);
        $newValue = $value;

        if (strpos($str, $oldValue) !== false) {
            $str = str_replace("{$oldValue}", "{$newValue}", $str);
            $fp = fopen($envFile, 'w');
            fwrite($fp, $str);
            fclose($fp);
        }

        \Artisan::call('config:clear -q');
    }
}
