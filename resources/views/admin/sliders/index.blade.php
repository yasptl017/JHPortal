@extends('admin.layouts.app')
@section('title', 'Slider Management')
@section('breadcrumb', 'Sliders')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <div>
        <h1 class="page-title">Slider Management</h1>
        <p class="page-subtitle">Manage homepage slider images and content</p>
    </div>
    <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary">
        <i class="fas fa-plus me-2"></i>Add Slider
    </a>
</div>

<div class="card">
    <div class="card-body">
        @if($sliders->isEmpty())
            <div class="text-center py-5">
                <i class="fas fa-images fa-3x text-muted mb-3"></i>
                <h5 class="text-muted">No sliders yet</h5>
                <p class="text-muted">Add your first slider to showcase on the homepage.</p>
                <a href="{{ route('admin.sliders.create') }}" class="btn btn-primary mt-2">Add Slider</a>
            </div>
        @else
            <div class="table-responsive">
                <table class="table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Title</th>
                            <th>Subtitle</th>
                            <th>Order</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($sliders as $slider)
                            <tr>
                                <td>
                                    <img src="{{ asset('storage/' . $slider->image) }}" alt="{{ $slider->title }}"
                                         style="width:120px;height:60px;object-fit:cover;border-radius:8px;">
                                </td>
                                <td style="font-weight:600;">{{ $slider->title ?? '-' }}</td>
                                <td>{{ Str::limit($slider->subtitle, 40) ?? '-' }}</td>
                                <td><span class="badge bg-secondary">{{ $slider->sort_order }}</span></td>
                                <td>
                                    @if($slider->is_active)
                                        <span class="badge badge-success">Active</span>
                                    @else
                                        <span class="badge badge-danger">Inactive</span>
                                    @endif
                                </td>
                                <td>
                                    <div class="d-flex gap-2">
                                        <a href="{{ route('admin.sliders.edit', $slider) }}" class="btn btn-sm btn-secondary">
                                            <i class="fas fa-edit"></i>
                                        </a>
                                        <form method="POST" action="{{ route('admin.sliders.destroy', $slider) }}" onsubmit="return confirm('Delete this slider?');">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-danger"><i class="fas fa-trash"></i></button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>
@endsection
