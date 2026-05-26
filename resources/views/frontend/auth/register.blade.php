@extends('frontend.layouts.app')
@section('title', 'Register')

@section('content')
<section class="section" style="min-height:70vh;display:flex;align-items:center;">
    <div class="container">
        <div class="row justify-content-center">
            <div class="col-md-5">
                <div class="text-center mb-4">
                    <div style="width:64px;height:64px;border-radius:16px;background:linear-gradient(135deg,var(--primary),var(--secondary));display:flex;align-items:center;justify-content:center;margin:0 auto 16px;">
                        <i class="fas fa-user-plus" style="font-size:1.8rem;color:#fff;"></i>
                    </div>
                    <h2 style="font-weight:800;color:var(--dark);">Create Account</h2>
                    <p style="color:var(--gray);">Join the Jewish House community today</p>
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

                        <form method="POST" action="{{ route('register') }}">
                            @csrf
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Full Name <span style="color:var(--danger);">*</span></label>
                                <input type="text" name="name" class="form-control" placeholder="Your full name" value="{{ old('name') }}" required style="border-radius:8px;">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Email Address <span style="color:var(--danger);">*</span></label>
                                <input type="email" name="email" class="form-control" placeholder="your@email.com" value="{{ old('email') }}" required style="border-radius:8px;">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Phone Number</label>
                                <input type="tel" name="phone" class="form-control" placeholder="(02) 9300 0000" value="{{ old('phone') }}" style="border-radius:8px;">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Password <span style="color:var(--danger);">*</span></label>
                                <input type="password" name="password" class="form-control" placeholder="Minimum 8 characters" required style="border-radius:8px;">
                            </div>
                            <div class="mb-3">
                                <label class="form-label fw-semibold">Confirm Password <span style="color:var(--danger);">*</span></label>
                                <input type="password" name="password_confirmation" class="form-control" placeholder="Re-enter password" required style="border-radius:8px;">
                            </div>
                            <button type="submit" class="btn btn-primary-custom w-100" style="padding:12px;">
                                <i class="fas fa-user-plus me-2"></i>Create Account
                            </button>
                        </form>

                        <div class="text-center mt-4" style="font-size:0.9rem;">
                            Already have an account? <a href="{{ route('login') }}" style="color:var(--primary);font-weight:600;text-decoration:none;">Sign in</a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
