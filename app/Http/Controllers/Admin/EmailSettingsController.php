<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailConfiguration;
use Illuminate\Http\Request;

class EmailSettingsController extends Controller
{
    public function index()
    {
        $config = EmailConfiguration::first();
        return view('admin.email-settings.index', compact('config'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'mailer' => 'required|string|in:smtp,log',
            'host' => 'required_if:mailer,smtp|nullable|string',
            'port' => 'required_if:mailer,smtp|nullable|integer|min:1|max:65535',
            'username' => 'required_if:mailer,smtp|nullable|string',
            'password' => 'required_if:mailer,smtp|nullable|string',
            'encryption' => 'required_if:mailer,smtp|nullable|string|in:tls,ssl',
            'from_address' => 'required|email',
            'from_name' => 'required|string|max:255',
            'is_active' => 'boolean',
        ]);

        $config = EmailConfiguration::first() ?? new EmailConfiguration();
        $config->fill($validated);
        $config->save();

        return redirect()->route('admin.email-settings.index')
            ->with('success', 'Email configuration saved successfully!');
    }

    public function testEmail(Request $request)
    {
        $request->validate([
            'test_email' => 'required|email',
        ]);

        try {
            $config = EmailConfiguration::first();
            if (!$config) {
                return back()->with('error', 'Email configuration not found. Please configure email settings first.');
            }

            $config->applyToConfig();

            \Mail::raw('This is a test email from JHPortal.', function ($message) use ($request, $config) {
                $message->to($request->test_email)
                    ->subject('Test Email from JHPortal')
                    ->from($config->from_address, $config->from_name);
            });

            return back()->with('success', 'Test email sent successfully!');
        } catch (\Exception $e) {
            // Log the actual error for debugging
            \Log::error('Email test failed: ' . $e->getMessage(), ['exception' => $e]);
            
            // Return generic error message to user
            return back()->with('error', 'Unable to send test email. Please check your email configuration and try again.');
        }
    }
}
