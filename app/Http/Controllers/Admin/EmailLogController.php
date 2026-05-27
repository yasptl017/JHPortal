<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\EmailLog;
use Illuminate\Http\Request;

class EmailLogController extends Controller
{
    public function index(Request $request)
    {
        $query = EmailLog::with('user', 'event')->orderBy('created_at', 'desc');

        // Filter by type
        if ($request->has('type') && $request->type) {
            $query->where('type', $request->type);
        }

        // Filter by status
        if ($request->has('status') && $request->status) {
            $query->where('status', $request->status);
        }

        // Search by email
        if ($request->has('search') && $request->search) {
            $query->where('recipient_email', 'like', '%' . $request->search . '%');
        }

        $emailLogs = $query->paginate(20);

        $stats = [
            'total_sent' => EmailLog::where('status', 'sent')->count(),
            'total_pending' => EmailLog::where('status', 'pending')->count(),
            'total_failed' => EmailLog::where('status', 'failed')->count(),
            'total_emails' => EmailLog::count(),
        ];

        return view('admin.email-logs.index', compact('emailLogs', 'stats'));
    }

    public function show(EmailLog $emailLog)
    {
        return view('admin.email-logs.show', compact('emailLog'));
    }

    public function resend(EmailLog $emailLog)
    {
        if ($emailLog->status === 'sent') {
            return back()->with('error', 'Cannot resend an already sent email.');
        }

        try {
            \Mail::raw($emailLog->body, function ($message) use ($emailLog) {
                $message->to($emailLog->recipient_email)
                    ->subject($emailLog->subject)
                    ->from(config('mail.from.address'), config('mail.from.name'));
            });

            $emailLog->markAsSent();
            return back()->with('success', 'Email resent successfully.');
        } catch (\Exception $e) {
            // Log the actual error for debugging
            \Log::error('Email resend failed: ' . $e->getMessage(), ['exception' => $e]);
            
            $emailLog->markAsFailed($e->getMessage());
            
            // Return generic error message to user
            return back()->with('error', 'Unable to resend email. Please try again later.');
        }
    }

    public function destroy(EmailLog $emailLog)
    {
        $emailLog->delete();
        return back()->with('success', 'Email log deleted successfully.');
    }
}
