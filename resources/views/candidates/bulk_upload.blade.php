@extends('layouts.layout') {{-- Ensure you have a layout with sidebar --}}
@section('title', 'Bulk Upload Candidates')

@section('content')
<div class="content">
    <h2>Bulk Upload Candidates</h2>

    @if (session('error'))
        <div class="error">{{ session('error') }}</div>
    @endif

    <form id="uploadForm" method="POST" enctype="multipart/form-data" action="{{ route('candidates.bulk-upload') }}">

        @csrf
        <label for="zip_file">Upload ZIP (containing CSV):</label>
        <input type="file" name="zip_file" id="zip_file" accept=".zip" required>
        <button type="submit">Upload</button>
    </form>

    <progress id="progressBar" value="0" max="100" style="display: none;"></progress>
    <p id="statusText"></p>
    <div id="uploadResult"></div>
</div>
@endsection

@push('scripts')
<script>
document.getElementById('uploadForm').onsubmit = function (e) {
    e.preventDefault();
    let form = e.target;
    let formData = new FormData(form);
    let xhr = new XMLHttpRequest();

    document.getElementById('progressBar').style.display = 'block';

    xhr.open('POST', form.action, true);
    xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');

    xhr.upload.onprogress = function (e) {
        if (e.lengthComputable) {
            let percent = Math.round((e.loaded / e.total) * 100);
            document.getElementById('progressBar').value = percent;
            document.getElementById('statusText').innerText = percent + '% uploaded';
        }
    };

    xhr.onload = function () {
        document.getElementById('uploadResult').innerHTML = this.responseText;
        document.getElementById('progressBar').style.display = 'none';
        document.getElementById('statusText').innerText = '';
    };

    xhr.send(formData);
};
</script>
@endpush

