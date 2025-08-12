{{-- resources/views/user_management/roles.blade.php --}}

@extends('layouts.layout')

@section('title', 'Role Management')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between mb-3">
        <h2>🔐 Role Management</h2>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addRoleModal">➕ Add Role</button>
    </div>

    <table class="table table-bordered bg-white">
        <thead class="table-dark">
            <tr>
                <th>S/N</th>
                <th>Name</th>
                <th>Description</th>
                <th>Status</th>
                <th>Time Created</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
        @forelse ($roles as $index => $role)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ $role->name }}</td>
                <td>{{ $role->description }}</td>
                <td>
                    @if ($role->status)
                        <span class="badge bg-success">Active</span>
                    @else
                        <span class="badge bg-secondary">Disabled</span>
                    @endif
                </td>
                <td>{{ $role->created_at->format('Y-m-d H:i') }}</td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-dark dropdown-toggle" data-bs-toggle="dropdown">Action</button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('roles.edit', $role->id) }}">Edit</a></li>
                            <li><form method="POST" action="{{ route('roles.destroy', $role->id) }}">
                                @csrf @method('DELETE')
                                <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Delete this role?')">Delete</button>
                            </form></li>
                        </ul>
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="6">No roles found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<!-- Modals can stay here, wrapped in @if as needed -->

@endsection

