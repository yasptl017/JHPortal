<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\SiteSetting;
use Illuminate\Http\Request;

class SiteSettingController extends Controller
{
    public function index()
    {
        $settings = [
            'site_name' => SiteSetting::get('site_name', 'Jewish House'),
            'site_description' => SiteSetting::get('site_description', 'Event & Community Engagement Portal'),
            'contact_email' => SiteSetting::get('contact_email', ''),
            'contact_phone' => SiteSetting::get('contact_phone', ''),
            'contact_address' => SiteSetting::get('contact_address', ''),
            'facebook_url' => SiteSetting::get('facebook_url', ''),
            'twitter_url' => SiteSetting::get('twitter_url', ''),
            'instagram_url' => SiteSetting::get('instagram_url', ''),
            'footer_text' => SiteSetting::get('footer_text', ''),
        ];

        return view('admin.settings.index', compact('settings'));
    }

    public function update(Request $request)
    {
        $validated = $request->validate([
            'site_name' => 'required|string|max:255',
            'site_description' => 'nullable|string|max:500',
            'contact_email' => 'nullable|email|max:255',
            'contact_phone' => 'nullable|string|max:20',
            'contact_address' => 'nullable|string|max:500',
            'facebook_url' => 'nullable|url|max:255',
            'twitter_url' => 'nullable|url|max:255',
            'instagram_url' => 'nullable|url|max:255',
            'footer_text' => 'nullable|string|max:500',
        ]);

        foreach ($validated as $key => $value) {
            SiteSetting::set($key, $value);
        }

        return back()->with('success', 'Settings updated successfully.');
    }
}
