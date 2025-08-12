{{-- resources/views/user_management/roles.blade.php --}}
@extends('layouts.layout')
@section('title', 'Role Management')

@section('content')
<div class="container py-4">
    <div class="d-flex justify-content-between mb-3">
        <h2>🔐 Role Management</h2>
        <button class="btn btn-success" data-bs-toggle="modal" data-bs-target="#addRoleModal">➕ Add Role</button>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

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
                    <span class="badge {{ $role->status ? 'bg-success' : 'bg-secondary' }}">
                        {{ $role->status ? 'Active' : 'Disabled' }}
                    </span>
                </td>
                <td>{{ $role->created_at ? $role->created_at->format('Y-m-d H:i') : 'N/A' }}</td>
                <td>
                    <div class="dropdown">
                        <button class="btn btn-sm btn-dark dropdown-toggle" data-bs-toggle="dropdown">Action</button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('roles.edit', $role->id) }}">Edit</a></li>
                            <li>
                                <form method="POST" action="{{ route('roles.destroy', $role->id) }}">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="dropdown-item text-danger" onclick="return confirm('Delete this role?')">Delete</button>
                                </form>
                            </li>
                            <li><a class="dropdown-item text-warning" href="{{ route('roles.disable', $role->id) }}">Disable</a></li>
                            <li><a class="dropdown-item text-success" href="{{ route('roles.permissions', $role->id) }}">Permissions</a></li>
                        </ul>
                    </div>
                </td>
            </tr>
        @empty
            <tr><td colspan="6" class="text-center">No roles found.</td></tr>
        @endforelse
        </tbody>
    </table>
</div>

<!-- Add Role Modal -->
<div class="modal fade" id="addRoleModal" tabindex="-1">
    <div class="modal-dialog">
        <form method="POST" action="{{ route('roles.store') }}" class="modal-content">
            @csrf
            <div class="modal-header">
                <h5 class="modal-title">Add Role</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <input type="text" name="name" placeholder="Role Name" class="form-control mb-2" required>
                <textarea name="description" placeholder="Description" class="form-control" required></textarea>
            </div>
            <div class="modal-footer">
                <button type="reset" class="btn btn-secondary">Clear</button>
                <button type="submit" class="btn btn-primary">Submit</button>
            </div>
        </form>
    </div>
</div>

<!-- Edit Role Modal -->
@if (!empty($editRole))
    <div class="modal fade show" id="editRoleModal" style="display: block;" tabindex="-1" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('roles.update', $editRole->id) }}" class="modal-content">
                @csrf
                @method('PUT')
                <div class="modal-header">
                    <h5 class="modal-title">Edit Role</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="text" name="name" class="form-control mb-2" value="{{ $editRole->name }}" required>
                    <textarea name="description" class="form-control" required>{{ $editRole->description }}</textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-warning">Update</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const editModal = new bootstrap.Modal(document.getElementById('editRoleModal'));
            editModal.show();
        });
    </script>
@endif

<!-- Permissions Modal -->
@if (!empty($permissionRole))
    <div class="modal fade show" id="permissionsModal" style="display: block;" tabindex="-1" aria-modal="true" role="dialog">
        <div class="modal-dialog">
            <form method="POST" action="{{ route('roles.update_permissions') }}" class="modal-content">
                @csrf
                <input type="hidden" name="role_id" value="{{ $permissionRole->id }}">
                <div class="modal-header">
                    <h5 class="modal-title">Permissions for {{ $permissionRole->name }}</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    @foreach ($allPermissions as $key => $label)
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="permissions[]" value="{{ $key }}" id="perm_{{ $key }}"
                                {{ in_array($key, $assigned ?? []) ? 'checked' : '' }}>
                            <label class="form-check-label" for="perm_{{ $key }}">{{ $label }}</label>
                        </div>
                    @endforeach
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success">Save</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const permModal = new bootstrap.Modal(document.getElementById('permissionsModal'));
            permModal.show();
        });
    </script>
@endif

@endsection

