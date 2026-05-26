@extends('frontend.layouts.app')
@section('title', 'Contact Us')

@section('content')
<div class="page-header">
    <div class="container">
        <h1>Contact Us</h1>
        <p>Get in touch with our team — we'd love to hear from you</p>
        <ul class="breadcrumb-custom">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li class="separator">/</li>
            <li class="current">Contact</li>
        </ul>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="row g-5">
            <!-- Contact Form -->
            <div class="col-lg-7">
                <div class="card" style="border-radius:16px;border:1px solid var(--border);">
                    <div class="card-body" style="padding:32px;">
                        <h3 style="font-weight:700;color:var(--dark);margin-bottom:8px;">Send Us a Message</h3>
                        <p style="color:var(--gray);margin-bottom:24px;">Fill out the form below and we'll get back to you as soon as possible.</p>

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

                        <form method="POST" action="{{ route('contact.store') }}">
                            @csrf
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Full Name <span style="color:var(--danger);">*</span></label>
                                    <input type="text" name="name" class="form-control" placeholder="Your name" value="{{ old('name') }}" required style="border-radius:8px;">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Email Address <span style="color:var(--danger);">*</span></label>
                                    <input type="email" name="email" class="form-control" placeholder="your@email.com" value="{{ old('email') }}" required style="border-radius:8px;">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Phone Number</label>
                                    <input type="tel" name="phone" class="form-control" placeholder="(02) 9300 0000" value="{{ old('phone') }}" style="border-radius:8px;">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label fw-semibold">Subject <span style="color:var(--danger);">*</span></label>
                                    <input type="text" name="subject" class="form-control" placeholder="How can we help?" value="{{ old('subject') }}" required style="border-radius:8px;">
                                </div>
                                <div class="col-12">
                                    <label class="form-label fw-semibold">Message <span style="color:var(--danger);">*</span></label>
                                    <textarea name="message" class="form-control" rows="5" placeholder="Tell us more about your enquiry..." required style="border-radius:8px;">{{ old('message') }}</textarea>
                                </div>
                                <div class="col-12">
                                    <button type="submit" class="btn btn-primary-custom" style="padding:12px 32px;">
                                        <i class="fas fa-paper-plane me-2"></i>Send Message
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            <!-- Contact Info -->
            <div class="col-lg-5">
                <div class="card mb-4" style="border-radius:16px;border:1px solid var(--border);">
                    <div class="card-body" style="padding:28px;">
                        <h5 style="font-weight:700;color:var(--dark);margin-bottom:20px;">Contact Information</h5>
                        <div style="display:flex;flex-direction:column;gap:20px;">
                            <div style="display:flex;gap:16px;align-items:flex-start;">
                                <div style="width:48px;height:48px;border-radius:12px;background:#dbeafe;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-envelope" style="color:var(--primary);font-size:1.1rem;"></i>
                                </div>
                                <div>
                                    <div style="font-weight:600;color:var(--dark);margin-bottom:2px;">Email</div>
                                    <a href="mailto:info@jewishhouse.org.au" style="color:var(--primary);text-decoration:none;">info@jewishhouse.org.au</a>
                                </div>
                            </div>
                            <div style="display:flex;gap:16px;align-items:flex-start;">
                                <div style="width:48px;height:48px;border-radius:12px;background:#dcfce7;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-phone" style="color:var(--success);font-size:1.1rem;"></i>
                                </div>
                                <div>
                                    <div style="font-weight:600;color:var(--dark);margin-bottom:2px;">Phone</div>
                                    <a href="tel:+61293000000" style="color:var(--gray);text-decoration:none;">(02) 9300 0000</a>
                                </div>
                            </div>
                            <div style="display:flex;gap:16px;align-items:flex-start;">
                                <div style="width:48px;height:48px;border-radius:12px;background:#ede9fe;display:flex;align-items:center;justify-content:center;flex-shrink:0;">
                                    <i class="fas fa-map-marker-alt" style="color:var(--secondary);font-size:1.1rem;"></i>
                                </div>
                                <div>
                                    <div style="font-weight:600;color:var(--dark);margin-bottom:2px;">Address</div>
                                    <span style="color:var(--gray);">Sydney, NSW, Australia</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="card" style="border-radius:16px;border:1px solid var(--border);">
                    <div class="card-body" style="padding:28px;">
                        <h5 style="font-weight:700;color:var(--dark);margin-bottom:16px;">Office Hours</h5>
                        <div style="display:flex;flex-direction:column;gap:8px;">
                            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--border);">
                                <span style="font-weight:600;color:var(--dark);">Monday - Friday</span>
                                <span style="color:var(--gray);">9:00 AM - 5:00 PM</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;padding:8px 0;border-bottom:1px solid var(--border);">
                                <span style="font-weight:600;color:var(--dark);">Saturday</span>
                                <span style="color:var(--gray);">Closed</span>
                            </div>
                            <div style="display:flex;justify-content:space-between;padding:8px 0;">
                                <span style="font-weight:600;color:var(--dark);">Sunday</span>
                                <span style="color:var(--gray);">Closed</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
