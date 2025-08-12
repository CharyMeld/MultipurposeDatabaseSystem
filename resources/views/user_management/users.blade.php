@extends('layouts.app') <!-- Adjust as per your layout -->

@section('content')
<style>
    /* Include your full CSS here exactly as-is */
    .wrapper {
        display: flex;
        min-height: 100vh;
    }

    .sidebar {
        width: 250px;
        background-color: #2c3e50;
        color: #fff;
        position: fixed;
        top: 0;
        bottom: 0;
        left: 0;
        padding: 20px;
        overflow-y: auto;
        z-index: 1000;
    }

    .main-content {
        margin-left: 250px;
        padding: 40px 30px;
        background: #f4f6f9;
        width: 100%;
        min-height: 100vh;
    }

    .container {
        padding: 20px;
        background: #fff;
        border-radius: 8px;
    }

    h2 {
        color: #2f4f4f;
        margin-bottom: 20px;
    }

    .add-user-btn {
        background-color: #2f4f4f;
        color: white;
        border: none;
        padding: 10px 15px;
        border-radius: 6px;
        cursor: pointer;
    }

    .add-user-btn:hover {
        background-color: #006400;
    }

    .filter-role {
        float: right;
        margin-bottom: 15px;
    }

    #addUserFormContainer {
        display: none;
        background: #f5f5f5;
        padding: 20px;
        margin-bottom: 30px;
        border-left: 5px solid #2f4f4f;
        border-radius: 8px;
    }

    .form-group {
        display: inline-block;
        width: 32%;
        margin-right: 1%;
        margin-bottom: 15px;
    }

    .form-group label {
        display: block;
        font-weight: bold;
        margin-bottom: 6px;
    }

    .form-group input,
    .form-group select {
        width: 100%;
        padding: 8px;
        border: 1px solid #ccc;
        border-radius: 5px;
    }

    table {
        width: 100%;
        border-collapse: collapse;
    }

    th, td {
        padding: 12px;
        text-align: left;
        border-bottom: 1px solid #ddd;
    }

    th {
        background-color: black;
        color: white;
    }

    tr:hover {
        background-color: #f1f1f1;
    }

    .action-dropdown {
        position: relative;
        display: inline-block;
    }

    .action-toggle {
        background: black;
        color: white;
        padding: 5px 10px;
        border: none;
        border-radius: 4px;
        cursor: pointer;
    }

    .action-content {
        display: none;
        position: absolute;
        right: 0;
        background-color: white;
        min-width: 120px;
        box-shadow: 0px 2px 6px rgba(0, 0, 0, 0.2);
        z-index: 1;
        border-radius: 6px;
        overflow: hidden;
    }

    .action-content a {
        display: block;
        color: black;
        padding: 10px;
        text-decoration: none;
    }

    .action-content a:hover {
        background-color: #2f4f4f;
        color: white;
    }

    @media (max-width: 768px) {
        .sidebar {
            position: relative;
            width: 100%;
        }

        .main-content {
            margin-left: 0;
            padding: 20px;
        }
    }
</style>

<div class="wrapper">
    <!-- Sidebar -->
    <div class="sidebar">
        @include('admin.partials.sidebar') {{-- adjust to your actual sidebar blade path --}}
    </div>

    <!-- Main Content -->
    <div class="main-content">
        <div class="container">
            <h2>Manage Users</h2>

            <button class="add-user-btn" onclick="toggleAddUserForm()">+ Add User</button>

            <select id="roleFilter" class="filter-role">
                <option value="">-- Filter by Role --</option>
                @foreach ($roles as $role)
                    <option value="{{ $role->id }}">{{ $role->name }}</option>
                @endforeach
            </select>

            <div id="addUserFormContainer">
                <form action="{{ route('users.store') }}" method="POST">
                    @csrf
                    <div class="form-group">
                        <label>Login Name</label>
                        <input type="text" name="login_name" required>
                    </div>

                    <div class="form-group">
                        <label>Password</label>
                        <input type="password" name="login_password" required>
                    </div>

                    <div class="form-group">
                        <label>Firstname</label>
                        <input type="text" name="firstname" required>
                    </div>

                    <div class="form-group">
                        <label>Lastname</label>
                        <input type="text" name="lastname" required>
                    </div>

                    <div class="form-group">
                        <label>Middlename</label>
                        <input type="text" name="middlename">
                    </div>

                    <div class="form-group">
                        <label>Gender</label>
                        <select name="gender" required>
                            <option value="">Select gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Marital Status</label>
                        <select name="marital_status">
                            <option value="">Select status</option>
                            <option value="single">Single</option>
                            <option value="married">Married</option>
                        </select>
                    </div>

                    <div class="form-group">
                        <label>Role</label>
                        <select name="role_id" required>
                            <option value="">Select role</option>
                            @foreach ($roles as $role)
                                <option value="{{ $role->id }}">{{ $role->name }}</option>
                            @endforeach
                        </select>
                    </div>

                    <div class="form-group" style="width: 100%;">
                        <button type="submit" class="add-user-btn" style="margin-top: 10px;">Submit</button>
                    </div>
                </form>
            </div>

            <!-- User Table -->
            <table id="userTable">
                <thead>
                    <tr>
                        <th>S/N</th>
                        <th>Login Name</th>
                        <th>Firstname</th>
                        <th>Lastname</th>
                        <th>Middlename</th>
                        <th>Gender</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($users as $index => $user)
                        <tr data-role="{{ $user->role_id }}">
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $user->login_name }}</td>
                            <td>{{ $user->firstname }}</td>
                            <td>{{ $user->lastname }}</td>
                            <td>{{ $user->middlename }}</td>
                            <td>{{ ucfirst($user->gender) }}</td>
                            <td>{{ $user->status == 1 ? 'Active' : 'Inactive' }}</td>
                            <td>
                                <div class="action-dropdown">
                                    <button class="action-toggle">⋮</button>
                                    <div class="action-content">
                                        <a href="#">✏️ Edit</a>
                                        <a href="#">🚫 Disable</a>
                                        <a href="#">🗑️ Delete</a>
                                    </div>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

<script>
function toggleAddUserForm() {
    const form = document.getElementById("addUserFormContainer");
    form.style.display = form.style.display === "none" ? "block" : "none";
}

document.getElementById("roleFilter").addEventListener("change", function () {
    const role = this.value;
    document.querySelectorAll("#userTable tbody tr").forEach(row => {
        const userRole = row.getAttribute("data-role");
        row.style.display = (role === "" || userRole === role) ? "" : "none";
    });
});

document.querySelectorAll('.action-toggle').forEach(btn => {
    btn.addEventListener('click', function (e) {
        e.stopPropagation();
        document.querySelectorAll('.action-content').forEach(drop => {
            if (drop !== this.nextElementSibling) drop.style.display = 'none';
        });
        const dropdown = this.nextElementSibling;
        dropdown.style.display = dropdown.style.display === 'block' ? 'none' : 'block';
    });
});

window.addEventListener('click', () => {
    document.querySelectorAll('.action-content').forEach(drop => {
        drop.style.display = 'none';
    });
});
</script>
@endsection

