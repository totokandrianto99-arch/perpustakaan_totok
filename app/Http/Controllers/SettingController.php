<?php

namespace App\Http\Controllers;

use App\Models\Setting;

class SettingController extends Controller
{
    public function index()
    {
        $settings = Setting::defaults();
        foreach ($settings as $key => &$s) {
            $s['value'] = Setting::get($key, $s['value']);
        }
        return view('settings.index', compact('settings'));
    }

    public function update(\Illuminate\Http\Request $request)
    {
        foreach (Setting::defaults() as $key => $def) {
            if ($request->has($key)) {
                Setting::set($key, $request->input($key));
            }
        }
        return back()->with('success', 'Pengaturan berhasil disimpan!');
    }
}
