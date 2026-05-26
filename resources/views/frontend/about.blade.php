@extends('frontend.layouts.app')
@section('title', 'About Us')

@section('content')
<div class="page-header">
    <div class="container">
        <h1>About Jewish House</h1>
        <p>Learn about our mission, values, and community programs</p>
        <ul class="breadcrumb-custom">
            <li><a href="{{ route('home') }}">Home</a></li>
            <li class="separator">/</li>
            <li class="current">About</li>
        </ul>
    </div>
</div>

<section class="section">
    <div class="container">
        <div class="row g-5 align-items-center">
            <div class="col-lg-6">
                <h2 style="font-size:2rem;font-weight:800;color:var(--dark);margin-bottom:16px;">Our Mission</h2>
                <p style="color:var(--gray);font-size:1.05rem;line-height:1.8;margin-bottom:20px;">
                    Jewish House is dedicated to building a stronger, more connected community through meaningful events, 
                    educational programs, and support services. We bring people together through shared experiences and 
                    create opportunities for personal growth and community engagement.
                </p>
                <p style="color:var(--gray);font-size:1.05rem;line-height:1.8;">
                    Our Event & Community Engagement Portal makes it easy for community members to discover, 
                    register for, and participate in our diverse range of programs — from JH Kids workshops 
                    to SMART Recovery meetings, movement programs, and parent support groups.
                </p>
            </div>
            <div class="col-lg-6">
                <div style="background:linear-gradient(135deg,#667eea 0%,#764ba2 100%);border-radius:20px;padding:60px;display:flex;align-items:center;justify-content:center;">
                    <i class="fas fa-synagogue" style="font-size:8rem;color:rgba(255,255,255,0.3);"></i>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Programs Section -->
<section class="section section-light">
    <div class="container">
        <div class="text-center mb-5">
            <h2 class="section-title">Our Programs</h2>
            <p class="section-subtitle">Diverse programs designed to support and empower our community</p>
        </div>
        <div class="row g-4">
            <div class="col-lg-4 col-md-6">
                <div class="card h-100" style="border-radius:16px;border:1px solid var(--border);">
                    <div style="height:8px;background:linear-gradient(90deg,#2563eb,#3b82f6);border-radius:16px 16px 0 0;"></div>
                    <div class="card-body" style="padding:28px;">
                        <div style="width:56px;height:56px;border-radius:14px;background:#dbeafe;display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
                            <i class="fas fa-child" style="font-size:1.5rem;color:#2563eb;"></i>
                        </div>
                        <h5 style="font-weight:700;color:var(--dark);">JH Kids</h5>
                        <p style="color:var(--gray);font-size:0.9rem;">Fun and educational workshops designed for children, fostering creativity, learning, and community connection through engaging activities.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card h-100" style="border-radius:16px;border:1px solid var(--border);">
                    <div style="height:8px;background:linear-gradient(90deg,#059669,#10b981);border-radius:16px 16px 0 0;"></div>
                    <div class="card-body" style="padding:28px;">
                        <div style="width:56px;height:56px;border-radius:14px;background:#dcfce7;display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
                            <i class="fas fa-hand-holding-heart" style="font-size:1.5rem;color:#059669;"></i>
                        </div>
                        <h5 style="font-weight:700;color:var(--dark);">SMART Recovery</h5>
                        <p style="color:var(--gray);font-size:0.9rem;">Evidence-based support meetings helping individuals manage and overcome addictive behaviours in a safe and supportive environment.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card h-100" style="border-radius:16px;border:1px solid var(--border);">
                    <div style="height:8px;background:linear-gradient(90deg,#d97706,#f59e0b);border-radius:16px 16px 0 0;"></div>
                    <div class="card-body" style="padding:28px;">
                        <div style="width:56px;height:56px;border-radius:14px;background:#fed7aa;display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
                            <i class="fas fa-running" style="font-size:1.5rem;color:#d97706;"></i>
                        </div>
                        <h5 style="font-weight:700;color:var(--dark);">Movement Programs</h5>
                        <p style="color:var(--gray);font-size:0.9rem;">Physical wellness programs including yoga, dance, and fitness activities promoting health and wellbeing for all ages.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card h-100" style="border-radius:16px;border:1px solid var(--border);">
                    <div style="height:8px;background:linear-gradient(90deg,#7c3aed,#8b5cf6);border-radius:16px 16px 0 0;"></div>
                    <div class="card-body" style="padding:28px;">
                        <div style="width:56px;height:56px;border-radius:14px;background:#ede9fe;display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
                            <i class="fas fa-people-arrows" style="font-size:1.5rem;color:#7c3aed;"></i>
                        </div>
                        <h5 style="font-weight:700;color:var(--dark);">Parent Programs</h5>
                        <p style="color:var(--gray);font-size:0.9rem;">Support groups and workshops for parents, providing resources, guidance, and community connection for families.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card h-100" style="border-radius:16px;border:1px solid var(--border);">
                    <div style="height:8px;background:linear-gradient(90deg,#db2777,#ec4899);border-radius:16px 16px 0 0;"></div>
                    <div class="card-body" style="padding:28px;">
                        <div style="width:56px;height:56px;border-radius:14px;background:#fce7f3;display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
                            <i class="fas fa-chalkboard-teacher" style="font-size:1.5rem;color:#db2777;"></i>
                        </div>
                        <h5 style="font-weight:700;color:var(--dark);">Community Workshops</h5>
                        <p style="color:var(--gray);font-size:0.9rem;">Educational sessions and skill-building workshops covering a wide range of topics relevant to our community.</p>
                    </div>
                </div>
            </div>
            <div class="col-lg-4 col-md-6">
                <div class="card h-100" style="border-radius:16px;border:1px solid var(--border);">
                    <div style="height:8px;background:linear-gradient(90deg,#0ea5e9,#38bdf8);border-radius:16px 16px 0 0;"></div>
                    <div class="card-body" style="padding:28px;">
                        <div style="width:56px;height:56px;border-radius:14px;background:#e0f2fe;display:flex;align-items:center;justify-content:center;margin-bottom:16px;">
                            <i class="fas fa-hands-helping" style="font-size:1.5rem;color:#0ea5e9;"></i>
                        </div>
                        <h5 style="font-weight:700;color:var(--dark);">Support Services</h5>
                        <p style="color:var(--gray);font-size:0.9rem;">Comprehensive support services including counselling, crisis support, and community outreach programs.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>

<!-- Stats Section -->
<section class="section">
    <div class="container">
        <div class="row g-4 text-center">
            <div class="col-lg-3 col-6">
                <div style="padding:24px;">
                    <div style="font-size:2.5rem;font-weight:800;color:var(--primary);">500+</div>
                    <div style="color:var(--gray);font-weight:600;">Community Members</div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div style="padding:24px;">
                    <div style="font-size:2.5rem;font-weight:800;color:var(--success);">100+</div>
                    <div style="color:var(--gray);font-weight:600;">Events Per Year</div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div style="padding:24px;">
                    <div style="font-size:2.5rem;font-weight:800;color:var(--secondary);">6</div>
                    <div style="color:var(--gray);font-weight:600;">Program Categories</div>
                </div>
            </div>
            <div class="col-lg-3 col-6">
                <div style="padding:24px;">
                    <div style="font-size:2.5rem;font-weight:800;color:#ea580c;">20+</div>
                    <div style="color:var(--gray);font-weight:600;">Years of Service</div>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection
