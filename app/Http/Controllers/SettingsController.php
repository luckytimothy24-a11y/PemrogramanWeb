<?php

namespace App\Http\Controllers;

use App\Services\SettingsService;
use Illuminate\Http\Request;

class SettingsController extends Controller
{
    protected $settingsService;

    public function __construct(SettingsService $settingsService)
    {
        $this->settingsService = $settingsService;
    }

    public function index()
    {
        return view('settings.index');
    }

    public function update(Request $request)
    {
        $request->validate([
            'app_name' => ['required', 'string', 'max:100'],
            'app_logo' => ['nullable', 'image', 'mimes:jpg,jpeg,png,webp,gif,svg', 'max:2048'],
        ]);

        $this->settingsService->update($request->all(), $request->file('app_logo'));

        return redirect()->route('settings.index')->with('success', 'Pengaturan berhasil disimpan!');
    }

    public function destroyLogo()
    {
        $this->settingsService->deleteLogo();
        $this->settingsService->set('app_logo', null);

        return redirect()->route('settings.index')->with('success', 'Logo aplikasi berhasil dihapus.');
    }
}
