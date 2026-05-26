@extends('frontend.layouts.app')
@section('title', 'Login')

@section('content')
<section class="section" style="min-height:70vh;display:flex;align-items:center;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="text-center mb-4">
                    <div style="width:64px;height:64px;border-radius:16px;background:linear-gradient(135deg,var(--primary),var(--secondary));display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                        <i class="fas fa-synagogue" style="font-size:1.8rem;color:#fff;"></i>
                    </div>
                    <h2 style="font-weight:800;color:var(--dark);">Welcome Back</h2>
                    <p style="color:var(--gray);">Sign in to your Jewish House account</p>
                </div>

                <div class="card" style="border-radius:16px;border:1px solid var(--border);">
                    <div class="card-body" style="padding:32px;">
                        @if($errors->any())
                            <div class="alert alert-danger alert-custom">
                                <i class="fas fa-exclamation-circle"></i>
                                <div>
                                    @foreach($errors->all() as $error)
                                        <div>{{ $error }}</div>
                                    @endforeach
                                </div>
                            </div>
                        @endif

                        <form method="POST" action="{{ route('login') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Email Address</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-envelope text-muted"></i></span>
                                    <input type="email" name="email" class="form-control" placeholder="your@email.com" value="{{ old('email') }}" required autofocus style="border-radius:0 8px 8px 0;">
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Password</label>
                                <div class="input-group">
                                    <span class="input-group-text bg-white"><i class="fas fa-lock text-muted"></i></span>
                                    <input type="password" name="password" class="form-control" placeholder="Enter your password" required style="border-radius:0 8px 8px 0;">
                                </div>
                            </div>
                            <div class="mb-3 d-flex justify-content-between align-items-center">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox" name="remember" id="remember">
                                    <label class="form-check-label" for="remember" style="font-size:0.9rem;">Remember me</label>
                                </div>
                            </div>
                            <button type="submit" class="btn btn-primary-custom w-100" style="padding:12px;">
                                <i class="fas fa-sign-in-alt me-2"></i>Sign In
                            </button>
                        </form>

                        <div class="text-center mt-4" style="font-size:0.9rem;">
                            Don't have an account? <a href="{{ route('register') }}" style="color:var(--primary);font-weight:600;text-decoration:none;">Create one</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
