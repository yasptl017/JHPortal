@extends('admin.layouts.app')
@section('title', $slider ? 'Edit Slider' : 'Add Slider')
@section('breadcrumb', $slider ? 'Edit Slider' : 'Add Slider')

@section('content')
<div class="mb-4">
    <h1 class="page-title">{{ $slider ? 'Edit Slider' : 'Add New Slider' }}</h1>
    <p class="page-subtitle">{{ $slider ? 'Update slider details' : 'Create a new slider for the homepage' }}</p>
</div>

<div class="card" style="max-width:800px;">
    <div class="card-body" style="padding:28px;">
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

        <form method="POST" action="{{ $slider ? route('admin.sliders.update', $slider) : route('admin.sliders.store') }}" enctype="multipart/form-data">
            @csrf
            @if($slider) @method('PUT') @endif

            <div class="row g-3">
                <div class="col-12">
                    <label class="form-label">Slider Image @if(!$slider)<span style="color:#ef4444;">*</span>@endif</label>
                    @if($slider && $slider->image)
                        <div class="mb-2">
                            <img src="{{ asset('storage/' . $slider->image) }}" alt="Current" style="width:100%;max-height:200px;object-fit:cover;border-radius:8px;">
                        </div>
                    @endif
                    <input type="file" name="image" class="form-control" accept="image/*" {{ $slider ? '' : 'required' }}>
                    <small class="text-muted">Recommended: 1920x550px. Max 2MB. JPEG, PNG, WebP.</small>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Title</label>
                    <input type="text" name="title" class="form-control" value="{{ old('title', $slider?->title) }}" placeholder="Slider heading text">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Subtitle</label>
                    <input type="text" name="subtitle" class="form-control" value="{{ old('subtitle', $slider?->subtitle) }}" placeholder="Short description">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Button Text</label>
                    <input type="text" name="button_text" class="form-control" value="{{ old('button_text', $slider?->button_text) }}" placeholder="e.g. Learn More">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Button Link</label>
                    <input type="text" name="button_link" class="form-control" value="{{ old('button_link', $slider?->button_link) }}" placeholder="/events">
                </div>
                <div class="col-md-6">
                    <label class="form-label">Sort Order</label>
                    <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $slider?->sort_order ?? 0) }}" min="0">
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <div class="form-check form-switch">
                        <input class="form-check-input" type="checkbox" name="is_active" value="1" id="isActive"
                            {{ old('is_active', $slider?->is_active ?? true) ? 'checked' : '' }}>
                        <label class="form-check-label fw-semibold" for="isActive">Active</label>
                    </div>
                </div>
            </div>

            <div class="d-flex gap-2 mt-4">
                <button type="submit" class="btn btn-primary"><i class="fas fa-save me-2"></i>{{ $slider ? 'Update' : 'Create' }} Slider</button>
                <a href="{{ route('admin.sliders.index') }}" class="btn btn-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
@endsection
