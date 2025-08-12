@extends('layouts.layout')

@section('content')
<div class="container mt-4">
    <h2>Add New Candidate</h2>

    {{-- Show validation errors --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Success message --}}
    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    <form method="POST" action="{{ route('candidates.store') }}">

        @csrf
        <div class="row">
            <div class="col-md-6 mb-3">
                <label for="registration_number">Registration Number</label>
                <input type="text" id="registration_number" name="registration_number" class="form-control" value="{{ old('registration_number') }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="surname">Surname</label>
                <input type="text" id="surname" name="surname" class="form-control" value="{{ old('surname') }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="other_name">Other Name</label>
                <input type="text" id="other_name" name="other_name" class="form-control" value="{{ old('other_name') }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="maiden_name">Maiden Name</label>
                <input type="text" id="maiden_name" name="maiden_name" class="form-control" value="{{ old('maiden_name') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label for="entry_mode">Entry Mode</label>
                <select id="entry_mode" name="entry_mode" class="form-control">
                    <option value="">Select</option>
                    <option value="Primary" {{ old('entry_mode') == 'Primary' ? 'selected' : '' }}>Primary</option>
                    <option value="Secondary" {{ old('entry_mode') == 'Secondary' ? 'selected' : '' }}>Secondary</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="country">Country</label>
                <select id="country" name="country" class="form-control" required>
                    <option value="">Select Country</option>
                    @foreach ($westAfricanCountries as $country)
                        <option value="{{ $country }}" {{ old('country') == $country ? 'selected' : '' }}>{{ $country }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="dob">Date of Birth</label>
                <input type="date" id="dob" name="dob" class="form-control" value="{{ old('dob') }}" required>
            </div>

            <div class="col-md-6 mb-3">
                <label for="gender">Gender</label>
                <select id="gender" name="gender" class="form-control" required>
                    <option value="">Select</option>
                    <option value="Male" {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                    <option value="Female" {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="nationality">Nationality</label>
                <select id="nationality" name="nationality" class="form-control" required>
                    <option value="">Select Nationality</option>
                    @foreach ($westAfricanCountries as $country)
                        <option value="{{ $country }}" {{ old('nationality') == $country ? 'selected' : '' }}>{{ $country }}</option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="fellowship_type">Fellowship Type</label>
                <select id="fellowship_type" name="fellowship_type" class="form-control">
                    <option value="">Select</option>
                    <option value="Full" {{ old('fellowship_type') == 'Full' ? 'selected' : '' }}>Full</option>
                    <option value="Associate" {{ old('fellowship_type') == 'Associate' ? 'selected' : '' }}>Associate</option>
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="faculty_id">Faculty</label>
                <select id="faculty_id" name="faculty_id" class="form-control" required>
                    <option value="">Select Faculty</option>
                    @foreach ($faculty as $faculty)
                        <option value="{{ $faculty->id }}" {{ old('faculty_id') == $faculty->id ? 'selected' : '' }}>
                            {{ $faculty->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="col-md-6 mb-3">
                <label for="sub_speciality">Sub Speciality</label>
                <input type="text" id="sub_speciality" name="sub_speciality" class="form-control" value="{{ old('sub_speciality') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label for="nysc_discharge_or_exemption">NYSC Discharge or Exemption</label>
                <input type="text" id="nysc_discharge_or_exemption" name="nysc_discharge_or_exemption" class="form-control" value="{{ old('nysc_discharge_or_exemption') }}">
            </div>

            <div class="col-md-6 mb-3">
                <label for="accredited_training_program">Accredited Training Program</label>
                <input type="text" id="accredited_training_program" name="accredited_training_program" class="form-control" value="{{ old('accredited_training_program') }}">
            </div>
        </div>

        <button type="submit" class="btn btn-primary mt-3">Save Candidate</button>
    </form>
</div>
@endsection

