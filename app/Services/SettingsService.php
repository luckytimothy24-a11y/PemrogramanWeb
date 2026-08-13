<?php

namespace App\Services;

use App\Models\Setting;
use Illuminate\Support\Facades\Storage;

class SettingsService
{
    public function all(): array
    {
        return Setting::pluck('value', 'key')->toArray();
    }

    public function get(string $key, $default = null)
    {
        return Setting::get($key, $default);
    }

    public function set(string $key, $value): void
    {
        Setting::set($key, $value);
    }

    public function update(array $data, $logo = null): void
    {
        if (! empty($data['app_name'])) {
            $this->set('app_name', trim($data['app_name']));
        }

        if ($logo) {
            $this->deleteLogo();
            $this->set('app_logo', $logo->store('settings', 'public'));
        }
    }

    public function deleteLogo(): void
    {
        $current = $this->get('app_logo');

        if ($current && Storage::disk('public')->exists($current)) {
            Storage::disk('public')->delete($current);
        }
    }
}
