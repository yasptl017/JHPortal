@extends('admin.layouts.app')
@section('title', 'Site Settings')
@section('breadcrumb', 'Settings')

@section('content')
<div class="mb-4">
    <h1 class="page-title">Site Settings</h1>
    <p class="page-subtitle">Configure website settings and contact information</p>
</div>

<div class="card" style="max-width:800px;">
    <div class="card-body" style="padding:28px;">
        <form method="POST" action="{{ route('admin.settings.update') }}">
            @csrf
            @method('PUT')

            @if($errors->any())
                <div class="alert-box alert-danger fade-in mb-3">
                    <i class="fas fa-exclamation-circle"></i>
                    <div>
                        @foreach($errors->all() as $error)
                            <div>{{ $error }}</div>
                        @endforeach
                    </div>
                </div>
            @endif

            <h5 style="font-weight:700;color:#1e293b;margin-bottom:16px;"><i class="fas fa-globe me-2" style="color:#2563eb;"></i>General</h5>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Site Name</label>
                    <input type="text" name="site_name" class="form-control" value="{{ old('site_name', $settings['site_name']) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Site Description</label>
                    <input type="text" name="site_description" class="form-control" value="{{ old('site_description', $settings['site_description']) }}">
                </div>
            </div>

            <hr class="my-4">

            <h5 style="font-weight:700;color:#1e293b;margin-bottom:16px;"><i class="fas fa-address-card me-2" style="color:#10b981;"></i>Contact Information</h5>
            <div class="row g-3 mb-4">
                <div class="col-md-6">
                    <label class="form-label">Contact Email</label>
                    <input type="email" name="contact_email" class="form-control" value="{{ old('contact_email', $settings['contact_email']) }}">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Contact Phone</label>
                    <input type="text" name="contact_phone" class="form-control" value="{{ old('contact_phone', $settings['contact_phone']) }}">
                </div>
                <div class="col-12">
                    <label class="form-label">Address</label>
                    <input type="text" name="contact_address" class="form-control" value="{{ old('contact_address', $settings['contact_address']) }}">
                </div>
            </div>

            <hr class="my-4">

            <h5 style="font-weight:700;color:#1e293b;margin-bottom:16px;"><i class="fas fa-share-alt me-2" style="color:#7c3aed;"></i>Social Media</h5>
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <label class="form-label">Facebook URL</label>
                    <input type="url" name="facebook_url" class="form-control" value="{{ old('facebook_url', $settings['facebook_url']) }}" placeholder="https://facebook.com/...">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Twitter URL</label>
                    <input type="url" name="twitter_url" class="form-control" value="{{ old('twitter_url', $settings['twitter_url']) }}" placeholder="https://twitter.com/...">
                </div>
                <div class="col-md-4">
                    <label class="form-label">Instagram URL</label>
                    <input type="url" name="instagram_url" class="form-control" value="{{ old('instagram_url', $settings['instagram_url']) }}" placeholder="https://instagram.com/...">
                </div>
            </div>

            <hr class="my-4">

            <h5 style="font-weight:700;color:#1e293b;margin-bottom:16px;"><i class="fas fa-paragraph me-2" style="color:#ea580c;"></i>Footer</h5>
            <div class="mb-4">
                <label class="form-label">Footer Text</label>
                <textarea name="footer_text" class="form-control" rows="3">{{ old('footer_text', $settings['footer_text']) }}</textarea>
            </div>

            <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>Save Settings</button>
        </form>
    </div>
</div>
@endsection
