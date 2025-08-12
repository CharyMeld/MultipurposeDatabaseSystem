@extends('layouts.layout')

@section('content')
<div class="container">
    <div class="row justify-content-center">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="mb-0">
                        <i class="fas fa-eye me-2"></i>Preview Candidates
                    </h4>
                    <p class="text-muted mb-0">Review the data below before saving to the database.</p>
                </div>

                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="fas fa-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if($errors->any())
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <h6><i class="fas fa-exclamation-triangle me-2"></i>Validation Errors:</h6>
                            <ul class="mb-0">
                                @foreach($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(isset($customErrors) && count($customErrors) > 0)
                        <div class="alert alert-warning alert-dismissible fade show" role="alert">
                            <h6><i class="fas fa-exclamation-circle me-2"></i>Processing Warnings:</h6>
                            <ul class="mb-0">
                                @foreach($customErrors as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(!empty($preview) && count($preview) > 0)
                        <div class="mb-3">
                            <div class="d-flex justify-content-between align-items-center">
                                <span class="badge bg-info fs-6">
                                    <i class="fas fa-users me-1"></i>{{ count($preview) }} candidate(s) ready for import
                                </span>
                                <div class="btn-group" role="group">
                                    <a href="{{ route('candidates.bulk-upload') }}" class="btn btn-outline-secondary">
                                        <i class="fas fa-arrow-left me-1"></i>Back to Upload
                                    </a>
                                </div>
                            </div>
                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-dark">
                                    <tr>
                                        <th scope="col">#</th>
                                        @foreach(array_keys($preview[0]) as $header)
                                            <th scope="col">
                                                {{ str_replace('_', ' ', ucwords($header, '_')) }}
                                            </th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($preview as $index => $row)
                                        <tr>
                                            <td class="fw-bold">{{ $index + 1 }}</td>
                                            @foreach($row as $key => $value)
                                                <td>
                                                    @if($key === 'dob' && $value)
                                                        {{ \Carbon\Carbon::parse($value)->format('M d, Y') }}
                                                    @elseif($key === 'gender')
                                                        <span class="badge bg-{{ $value === 'Male' ? 'primary' : 'pink' }}">
                                                            {{ $value }}
                                                        </span>
                                                    @elseif($key === 'registration_number')
                                                        <code>{{ $value }}</code>
                                                    @else
                                                        {{ $value ?: '-' }}
                                                    @endif
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>

                        <form method="POST" action="{{ route('candidates.save-bulk-preview') }}" class="mt-4"> 
                            @csrf
                            <input type="hidden" name="confirm_save" value="1">
                            
                            <div class="d-grid gap-2 d-md-flex justify-content-md-end">
                                <button type="button" class="btn btn-outline-danger me-md-2" onclick="confirmCancel()">
                                    <i class="fas fa-times me-1"></i>Cancel Import
                                </button>
                                <button type="submit" class="btn btn-success btn-lg" onclick="return confirmSave()">
                                    <i class="fas fa-save me-2"></i>Confirm & Save All ({{ count($preview) }})
                                </button>
                            </div>
                        </form>

                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="fas fa-inbox fa-3x text-muted"></i>
                            </div>
                            <h5 class="text-muted">No Data Available</h5>
                            <p class="text-muted">No candidate data found for preview.</p>
                            <a href="{{ route('candidates.bulk-upload') }}" class="btn btn-primary">
                                <i class="fas fa-upload me-1"></i>Upload Candidates
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
function confirmSave() {
    return confirm('Are you sure you want to save all {{ count($preview ?? []) }} candidate(s) to the database? This action cannot be undone.');
}

function confirmCancel() {
    if (confirm('Are you sure you want to cancel this import? All preview data will be lost.')) {
        window.location.href = '{{ route('candidates.bulk-upload') }}';
    }
}

// Auto-dismiss alerts after 5 seconds
document.addEventListener('DOMContentLoaded', function() {
    setTimeout(function() {
        var alerts = document.querySelectorAll('.alert-dismissible');
        alerts.forEach(function(alert) {
            var bsAlert = new bootstrap.Alert(alert);
            bsAlert.close();
        });
    }, 5000);
});
</script>
@endpush

@push('styles')
<style>
.table th {
    white-space: nowrap;
    font-weight: 600;
}

.table td {
    vertical-align: middle;
}

.badge.bg-pink {
    background-color: #e91e63 !important;
}

.card-header {
    background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
    color: white;
}

.btn-success {
    background: linear-gradient(135deg, #11998e 0%, #38ef7d 100%);
    border: none;
}

.btn-success:hover {
    background: linear-gradient(135deg, #0f8a7e 0%, #32d96a 100%);
    transform: translateY(-1px);
    box-shadow: 0 4px 8px rgba(0,0,0,0.2);
}
</style>
@endpush
@endsection


