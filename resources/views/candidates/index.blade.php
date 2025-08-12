@extends("layouts.layout")

@section("content")
<div class="wrapper">
    <div class="sidebar">
        <div class="logo">
            <img src="{{ asset('assets/images/logo.jpeg') }}" alt="WACS Logo" onerror="this.style.display='none'; this.nextElementSibling.style.display='block';">
            <div class="fallback-text">WACS</div>
        </div>
        @includeIf('partials.sidebar', ['sidebarNotFound' => 'Sidebar not found.'])
    </div>

    <div class="main-content">
        <h2>📋 All Candidates</h2>

        <div class="table-wrapper">
            <div class="top-bar">
                <a href="{{ url('candidates/add_candidate') }}">➕ Add Candidate</a>
                <a href="{{ route('candidates.bulk-upload') }}">Bulk Upload</a>
                <a href="{{ url('bulk_upload_passports') }}">🖼️ Bulk Upload Passports</a>
                <button onclick="toggleFilter()">🔍 Filter</button>
            </div>

            <form id="filter-form" class="filter-form" method="GET" action="{{ url()->current() }}">
                <input type="text" name="search" placeholder="Search name or reg. no." value="{{ old('search', $search) }}">
                <select name="faculty_filter">
                    <option value="">Filter by Faculty</option>
                    @foreach ($faculty as $f)
                        <option value="{{ $f->id }}" {{ $facultyFilter == $f->id ? 'selected' : '' }}>
                            {{ $f->name }}
                        </option>
                    @endforeach
                </select>
                <select name="entry_mode_filter">
                    <option value="">Filter by Entry Mode</option>
                    <option value="Primary" {{ $entryModeFilter=='Primary' ? 'selected' : '' }}>Primary</option>
                    <option value="Membership" {{ $entryModeFilter=='Membership' ? 'selected' : '' }}>Membership</option>
                    <option value="Fellowship" {{ $entryModeFilter=='Fellowship' ? 'selected' : '' }}>Fellowship</option>
                </select>
                <button type="submit">Apply</button>
            </form>

            @if ($candidates->count() > 0)
            <table id="candidatesTable">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Reg. No.</th>
                        <th>Surname</th>
                        <th>Other Name</th>
                        <th>Faculty</th>
                        <th>Entry Mode</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                @foreach ($candidates as $i => $c)
                    <tr>
                        <td>{{ $i + 1 }}</td>
                        <td>{{ $c->registration_number }}</td>
                        <td>{{ $c->surname }}</td>
                        <td>{{ $c->other_name }}</td>
                        <td>{{ $c->faculty->name ?? '' }}</td>
                        <td>{{ $c->entry_mode }}</td>
                        <td>
                            <div class="dropdown">
                                <button class="dropdown-btn">⚙️ Action</button>
                                <div class="dropdown-content">
                                    <a href="{{ route('candidates.profile.show', ['candidate' => $c->id]) }}">👁️ View</a>
                                    <a href="{{ url('edit_candidate?id=' . $c->id) }}">✏️ Edit</a>
                                    <a href="{{ url('disable_candidate?id=' . $c->id) }}" onclick="return confirm('Disable this candidate?')">🚫 Disable</a>
                                    <a href="{{ url('delete_candidate?id=' . $c->id) }}" onclick="return confirm('Delete this candidate?')">🗑️ Delete</a>
                                </div>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            @else
                <p>No candidates found.</p>
            @endif
        </div>
    </div>
</div>
@endsection

@section("scripts")
<script src="https://code.jquery.com/jquery-3.7.0.min.js"></script>
<script src="https://cdn.datatables.net/1.13.6/js/jquery.dataTables.min.js"></script>
<script>
    function toggleFilter() {
        const f = document.getElementById('filter-form');
        f.style.display = f.style.display === 'block' ? 'none' : 'block';
    }
    $(document).ready(function () {
        $('#candidatesTable').DataTable({
            "pageLength": 10,
            "order": [[0, "asc"]],
            "responsive": true
        });
    });
</script>
@endsection

