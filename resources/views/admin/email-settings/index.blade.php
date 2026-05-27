@extends('admin.layouts.app')

@section('title', 'Email Configuration')

@section('content')
<div class="container-fluid py-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1 class="h3 mb-0"><i class="fas fa-envelope me-2"></i>Email Configuration</h1>
            <p class="text-muted mt-2">Configure Gmail SMTP settings for sending event reminders and feedback emails</p>
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <strong>Validation Errors:</strong>
            <ul class="mb-0 mt-2">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            {{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">SMTP Configuration</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.email-settings.store') }}">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-bold">Mailer Type</label>
                            <select name="mailer" class="form-select @error('mailer') is-invalid @enderror" required>
                                <option value="smtp" @selected(($config?->mailer ?? 'smtp') === 'smtp')>SMTP</option>
                                <option value="log" @selected(($config?->mailer ?? 'smtp') === 'log')>Log (Testing)</option>
                            </select>
                            @error('mailer')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">SMTP Host</label>
                            <input type="text" name="host" class="form-control @error('host') is-invalid @enderror"
                                   value="{{ old('host', $config?->host ?? 'smtp.gmail.com') }}"
                                   placeholder="smtp.gmail.com">
                            <small class="text-muted">For Gmail: smtp.gmail.com</small>
                            @error('host')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">SMTP Port</label>
                                <input type="number" name="port" class="form-control @error('port') is-invalid @enderror"
                                       value="{{ old('port', $config?->port ?? 587) }}"
                                       placeholder="587">
                                <small class="text-muted">587 for TLS, 465 for SSL</small>
                                @error('port')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-bold">Encryption</label>
                                <select name="encryption" class="form-select @error('encryption') is-invalid @enderror">
                                    <option value="tls" @selected(($config?->encryption ?? 'tls') === 'tls')>TLS</option>
                                    <option value="ssl" @selected(($config?->encryption ?? 'tls') === 'ssl')>SSL</option>
                                </select>
                                @error('encryption')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">Email Address (Username)</label>
                            <input type="email" name="username" class="form-control @error('username') is-invalid @enderror"
                                   value="{{ old('username', $config?->username) }}"
                                   placeholder="your-email@gmail.com">
                            <small class="text-muted">Your Gmail address</small>
                            @error('username')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">App Password</label>
                            <input type="password" name="password" class="form-control @error('password') is-invalid @enderror"
                                   value="{{ old('password', $config?->password) }}"
                                   placeholder="Your Gmail App Password">
                            <small class="text-muted">
                                <a href="https://myaccount.google.com/apppasswords" target="_blank">Generate App Password</a>
                                (Not your regular Gmail password)
                            </small>
                            @error('password')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">From Email Address</label>
                            <input type="email" name="from_address" class="form-control @error('from_address') is-invalid @enderror"
                                   value="{{ old('from_address', $config?->from_address) }}"
                                   placeholder="noreply@example.com" required>
                            @error('from_address')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-bold">From Name</label>
                            <input type="text" name="from_name" class="form-control @error('from_name') is-invalid @enderror"
                                   value="{{ old('from_name', $config?->from_name ?? 'JHPortal') }}"
                                   placeholder="JHPortal" required>
                            @error('from_name')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>

                        <div class="mb-3">
                            <div class="form-check">
                                <input type="checkbox" id="is_active" name="is_active" value="1" class="form-check-input"
                                       @checked($config?->is_active ?? false)>
                                <label class="form-check-label" for="is_active">
                                    <strong>Activate this configuration</strong>
                                    <small class="d-block text-muted">Enable this configuration to use it for sending emails</small>
                                </label>
                            </div>
                        </div>

                        <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                            <button type="submit" class="btn btn-primary">
                                <i class="fas fa-save me-2"></i>Save Configuration
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card mb-4">
                <div class="card-header">
                    <h5 class="mb-0">Gmail Setup Guide</h5>
                </div>
                <div class="card-body">
                    <ol class="small">
                        <li>Enable 2-Step Verification on your Google Account</li>
                        <li>Go to <a href="https://myaccount.google.com/apppasswords" target="_blank">App Passwords</a></li>
                        <li>Select "Mail" and "Windows Computer"</li>
                        <li>Copy the generated 16-character password</li>
                        <li>Paste it in the "App Password" field above</li>
                        <li>Save the configuration</li>
                        <li>Test the connection using the button below</li>
                    </ol>
                </div>
            </div>

            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0">Test Email</h5>
                </div>
                <div class="card-body">
                    <form method="POST" action="{{ route('admin.email-settings.test') }}">
                        @csrf
                        <div class="mb-3">
                            <label class="form-label">Test Email Address</label>
                            <input type="email" name="test_email" class="form-control" required
                                   placeholder="your-email@example.com">
                        </div>
                        <button type="submit" class="btn btn-outline-primary w-100">
                            <i class="fas fa-paper-plane me-2"></i>Send Test Email
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
