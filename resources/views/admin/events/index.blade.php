@extends('layouts.admin')
@section('title', 'Events')

@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;margin-bottom:24px;flex-wrap:wrap;gap:12px;">
    <form method="GET" style="display:flex;gap:8px;flex-wrap:wrap;">
        <input type="text" name="search" class="form-input" placeholder="Cari event..." value="{{ request('search') }}" style="width:240px;">
        <select name="category" class="form-select" style="width:180px;" onchange="this.form.submit()">
            <option value="">Semua Kategori</option>
            @foreach($categories as $cat)
                <option value="{{ $cat->slug }}" {{ request('category') == $cat->slug ? 'selected' : '' }}>
                    {{ $cat->icon }} {{ $cat->name }}
                </option>
            @endforeach
        </select>
        <button type="submit" class="btn btn-secondary btn-sm"><i class="ri-search-line"></i></button>
    </form>
    <a href="{{ route('admin.events.create') }}" class="btn btn-primary">
        <i class="ri-add-line"></i> Tambah Event
    </a>
</div>

<div class="table-container">
    <table>
        <thead>
            <tr>
                <th>Kategori</th>
                <th>Judul</th>
                <th>Lokasi</th>
                <th>Sumber</th>
                <th>Waktu</th>
                <th>Aksi</th>
            </tr>
        </thead>
        <tbody>
            @forelse($events as $event)
            <tr>
                <td>
                    @if($event->category)
                        <span class="badge" style="background:{{ $event->category->color }}22;color:{{ $event->category->color }};border:1px solid {{ $event->category->color }}44;">
                            {{ $event->category->icon }} {{ $event->category->name }}
                        </span>
                    @else
                        <span class="badge" style="background:rgba(150,150,150,0.1);color:#999;">-</span>
                    @endif
                </td>
                <td style="max-width:280px;">
                    <div style="font-weight:500;">{{ Str::limit($event->title, 60) }}</div>
                </td>
                <td style="font-size:12px;color:var(--text-muted);">
                    {{ number_format($event->latitude, 4) }}, {{ number_format($event->longitude, 4) }}
                </td>
                <td style="font-size:12px;">{{ $event->source_name ?? '-' }}</td>
                <td style="font-size:12px;color:var(--text-secondary);">
                    {{ $event->published_at ? $event->published_at->diffForHumans() : '-' }}
                </td>
                <td>
                    <div style="display:flex;gap:6px;">
                        <a href="{{ route('admin.events.edit', $event) }}" class="btn btn-secondary btn-sm">
                            <i class="ri-edit-line"></i>
                        </a>
                        <form method="POST" action="{{ route('admin.events.destroy', $event) }}" onsubmit="return confirm('Hapus event ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger btn-sm"><i class="ri-delete-bin-line"></i></button>
                        </form>
                    </div>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="6" style="text-align:center;padding:40px;color:var(--text-muted);">
                    <i class="ri-inbox-line" style="font-size:32px;display:block;margin-bottom:8px;"></i>
                    Belum ada event
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>

@if($events->hasPages())
<div class="pagination">
    {{ $events->appends(request()->query())->links('pagination::simple-default') }}
</div>
@endif
@endsection
