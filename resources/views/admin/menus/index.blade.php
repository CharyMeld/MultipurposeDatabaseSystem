@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h2>Menu Management</h2>
        <div>
            <a href="{{ route('menus.create') }}" class="btn btn-primary me-2">+ Add New Menu</a>
            <a href="{{ route('menus.sync') }}" class="btn btn-outline-primary">Sync Menus</a>
        </div>
    </div>

    <div class="card">
        <div class="card-body">
            @if($menus->count())
                <ul class="list-group">
                    @foreach($menus->where('parent_id', null)->sortBy('id') as $menu)
                        @include('admin.menus.partials.menu_row', ['menu' => $menu])
                    @endforeach
                </ul>
            @else
                <p class="text-muted">No menus found.</p>
            @endif
        </div>
    </div>
</div>
@endsection

