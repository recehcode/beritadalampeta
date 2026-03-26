@extends('layouts.admin')
@section('title', isset($event) ? 'Edit Event' : 'Tambah Event')

@section('content')
<div class="card" style="max-width:700px;">
    <form method="POST" action="{{ isset($event) ? route('admin.events.update', $event) : route('admin.events.store') }}">
        @csrf
        @if(isset($event)) @method('PUT') @endif

        <div class="form-group">
            <label class="form-label">Judul *</label>
            <input type="text" name="title" class="form-input" value="{{ old('title', $event->title ?? '') }}" required>
        </div>

        <div class="form-group">
            <label class="form-label">Deskripsi</label>
            <textarea name="description" class="form-textarea">{{ old('description', $event->description ?? '') }}</textarea>
        </div>

        <div class="form-group">
            <label class="form-label">Kategori</label>
            <select name="category_slug" class="form-select">
                <option value="">Pilih Kategori</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->slug }}" {{ old('category_slug', $event->category_slug ?? '') == $cat->slug ? 'selected' : '' }}>
                        {{ $cat->icon }} {{ $cat->name }}
                    </option>
                @endforeach
            </select>
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
            <div class="form-group">
                <label class="form-label">Latitude *</label>
                <input type="number" step="any" name="latitude" class="form-input" value="{{ old('latitude', $event->latitude ?? '') }}" required placeholder="-6.2088">
            </div>
            <div class="form-group">
                <label class="form-label">Longitude *</label>
                <input type="number" step="any" name="longitude" class="form-input" value="{{ old('longitude', $event->longitude ?? '') }}" required placeholder="106.8456">
            </div>
        </div>

        <div class="form-group">
            <label class="form-label">URL Sumber</label>
            <input type="url" name="source_url" class="form-input" value="{{ old('source_url', $event->source_url ?? '') }}" placeholder="https://x.com/...">
        </div>

        <div class="form-group">
            <label class="form-label">URL Gambar</label>
            <input type="url" name="image_url" class="form-input" value="{{ old('image_url', $event->image_url ?? '') }}" placeholder="https://...">
        </div>

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:16px;">
            <div class="form-group">
                <label class="form-label">Nama Sumber</label>
                <input type="text" name="source_name" class="form-input" value="{{ old('source_name', $event->source_name ?? '') }}" placeholder="detikcom">
            </div>
            <div class="form-group">
                <label class="form-label">Waktu Publish</label>
                <input type="datetime-local" name="published_at" class="form-input" value="{{ old('published_at', isset($event->published_at) ? $event->published_at->format('Y-m-d\TH:i') : '') }}">
            </div>
        </div>

        <div style="display:flex;gap:12px;margin-top:8px;">
            <button type="submit" class="btn btn-primary">
                <i class="ri-save-line"></i> {{ isset($event) ? 'Update' : 'Simpan' }}
            </button>
            <a href="{{ route('admin.events.index') }}" class="btn btn-secondary">Batal</a>
        </div>
    </form>
</div>
@endsection
