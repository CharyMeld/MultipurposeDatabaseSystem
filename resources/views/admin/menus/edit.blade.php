@extends('layouts.layout')

@section('content')
<div class="container">
    <h2>Edit Menu</h2>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('menus.update', $menu->id) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="mb-3">
            <label for="title" class="form-label">Menu Title</label>
            <input type="text" class="form-control" name="title" id="title" value="{{ old('title', $menu->title) }}" required>
        </div>

        <div class="mb-3">
            <label for="route_name" class="form-label">Route Name</label>
            <input type="text" class="form-control" name="route_name" id="route_name" value="{{ old('route_name', $menu->route_name) }}">
            <small class="form-text text-muted">e.g. dashboard.index or leave blank if using URL instead</small>
        </div>

        <div class="mb-3">
            <label for="url" class="form-label">Custom URL</label>
            <input type="text" class="form-control" name="url" id="url" value="{{ old('url', $menu->url) }}">
            <small class="form-text text-muted">Optional if route name is set</small>
        </div>

        <div class="mb-3">
            <label for="parent_id" class="form-label">Parent Menu</label>
            <select name="parent_id" id="parent_id" class="form-select">
                <option value="">-- None --</option>
                @foreach ($menus as $parent)
                    <option value="{{ $parent->id }}" {{ $menu->parent_id == $parent->id ? 'selected' : '' }}>
                        {{ $parent->title }}
                    </option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label for="order" class="form-label">Order</label>
            <input type="number" class="form-control" name="order" id="order" value="{{ old('order', $menu->order) ?? 0 }}">
        </div>

        <button type="submit" class="btn btn-success">Update Menu</button>
        <a href="{{ route('menus.index') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection

