@extends('layouts.layout')

@section('content')
<div class="container">
    <h3>Create New Menu</h3>

    <form action="{{ route('menus.store') }}" method="POST">
        @csrf

        <div class="mb-3">
            <label>Title:</label>
            <input name="title" class="form-control" required>
        </div>

        <div class="mb-3">
            <label>Route Name (optional):</label>
            <input name="route_name" class="form-control">
        </div>

        <div class="mb-3">
            <label>Custom URL (optional):</label>
            <input name="url" class="form-control">
        </div>

        <div class="mb-3">
            <label>Parent Menu:</label>
            <select name="parent_id" class="form-select">
                <option value="">None</option>
                @foreach($menus as $m)
                    <option value="{{ $m->id }}">{{ $m->title }}</option>
                @endforeach
            </select>
        </div>

        <div class="mb-3">
            <label>Order:</label>
            <input type="number" name="order" class="form-control">
        </div>

        <button class="btn btn-success">Create</button>
    </form>
</div>
@endsection

