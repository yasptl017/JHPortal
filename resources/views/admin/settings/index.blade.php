@extends('admin.layouts.app')

@section('title', 'Settings')
@section('breadcrumb', 'Settings')

@section('content')
<div class="page-title">
    <i class="fas fa-cog" style="margin-right: 10px; color: var(--primary-color);"></i>
    Settings
</div>
<p class="page-subtitle">Configure your portal settings</p>

<div class="row">
    <div class="col-lg-3">
        <!-- Settings Menu -->
        <div class="card">
            <div class="card-body" style="padding: 0;">
                <div style="display: flex; flex-direction: column;">
                    <a href="#general" style="padding: 15px 20px; border-bottom: 1px solid #e2e8f0; color: var(--primary-color); text-decoration: none; font-weight: 600;">
                        <i class="fas fa-sliders-h"></i> General Settings
                    </a>
                    <a href="#email" style="padding: 15px 20px; border-bottom: 1px solid #e2e8f0; color: #64748b; text-decoration: none;">
                        <i class="fas fa-envelope"></i> Email Settings
                    </a>
                    <a href="#security" style="padding: 15px 20px; border-bottom: 1px solid #e2e8f0; color: #64748b; text-decoration: none;">
                        <i class="fas fa-shield-alt"></i> Security
                    </a>
                    <a href="#backup" style="padding: 15px 20px; color: #64748b; text-decoration: none;">
                        <i class="fas fa-database"></i> Backup
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-9">
        <!-- General Settings -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 style="margin: 0;">General Settings</h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Portal Name</label>
                            <input type="text" class="form-control" value="Jewish House Portal">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Portal Email</label>
                            <input type="email" class="form-control" value="portal@jewishhouse.com">
                        </div>
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Portal Description</label>
                        <textarea class="form-control" rows="3" placeholder="Enter portal description"></textarea>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Timezone</label>
                            <select class="form-control">
                                <option value="">Select Timezone</option>
                                <option value="UTC">UTC</option>
                                <option value="EST">EST</option>
                                <option value="PST">PST</option>
                            </select>
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Language</label>
                            <select class="form-control">
                                <option value="en">English</option>
                                <option value="es">Spanish</option>
                                <option value="fr">French</option>
                            </select>
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </form>
            </div>
        </div>

        <!-- Email Settings -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 style="margin: 0;">Email Settings</h5>
            </div>
            <div class="card-body">
                <form>
                    <div class="mb-3">
                        <label class="form-label">SMTP Server</label>
                        <input type="text" class="form-control" placeholder="smtp.gmail.com">
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">SMTP Port</label>
                            <input type="text" class="form-control" placeholder="587">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Encryption</label>
                            <select class="form-control">
                                <option value="tls">TLS</option>
                                <option value="ssl">SSL</option>
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Email Address</label>
                            <input type="email" class="form-control" placeholder="admin@jewishhouse.com">
                        </div>
                        <div class="col-md-6 mb-3">
                            <label class="form-label">Password</label>
                            <input type="password" class="form-control" placeholder="••••••••">
                        </div>
                    </div>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-save"></i> Save Changes
                    </button>
                </form>
            </div>
        </div>

        <!-- Security Settings -->
        <div class="card mb-4">
            <div class="card-header">
                <h5 style="margin: 0;">Security Settings</h5>
            </div>
            <div class="card-body">
                <div style="margin-bottom: 20px;">
                    <label class="form-label">Change Password</label>
                    <div class="row">
                        <div class="col-md-6 mb-2">
                            <input type="password" class="form-control" placeholder="Current Password">
                        </div>
                        <div class="col-md-6 mb-2">
                            <input type="password" class="form-control" placeholder="New Password">
                        </div>
                    </div>
                    <button type="button" class="btn btn-primary">Update Password</button>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection
