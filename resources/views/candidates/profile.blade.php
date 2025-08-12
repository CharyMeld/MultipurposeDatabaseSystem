@extends('layouts.layout')

@section('content')
<div class="mb-3">
    <a href="{{ url('/candidates') }}" class="btn btn-secondary">
        ← Back
    </a>
</div>

<div id="candidate-profile"></div>

<script>
    window.App = {
        csrfToken: "{{ csrf_token() }}",
        candidate: @json($candidate),
    };
</script>

@vite('resources/js/candidate-profile.js')
@endsection

