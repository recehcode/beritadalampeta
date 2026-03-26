<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\ApiSetting;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class SettingsController extends Controller
{
    public function index()
    {
        $settings = ApiSetting::all()->groupBy('setting_group');
        return view('admin.settings', compact('settings'));
    }

    public function update(Request $request)
    {
        $data = $request->except('_token');

        foreach ($data as $key => $value) {
            ApiSetting::where('setting_key', $key)->update([
                'setting_value' => $value ?? '',
            ]);
        }

        return redirect()->route('admin.settings')->with('success', 'Pengaturan berhasil disimpan!');
    }

    public function regenerateWebhookKey()
    {
        ApiSetting::setValue('webhook_api_key', Str::random(64));
        return redirect()->route('admin.settings')->with('success', 'Webhook API key berhasil di-generate ulang!');
    }
}
