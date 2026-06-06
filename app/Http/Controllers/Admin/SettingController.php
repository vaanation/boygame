<?php
namespace App\Http\Controllers\Admin;
use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;

class SettingController extends Controller {
    public function index() {
        $settings = Setting::all()->pluck('value', 'key');
        return view('admin.settings.index', compact('settings'));
    }
    public function update(Request $request) {
        foreach($request->except('_token') as $key => $value) {
            if($request->hasFile($key)) {
                $value = $request->file($key)->store('settings', 'public');
            }
            Setting::updateOrCreate(['key' => $key], ['value' => $value]);
        }
        return redirect()->back()->with('success', 'Pengaturan diperbarui.');
    }
}