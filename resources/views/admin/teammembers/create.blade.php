@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2>Add Team Member</h2>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    {{-- Validation Error Messages --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('member.store') }}" method="POST" enctype="multipart/form-data">
        @csrf

        <div class="form-group">
            <label>Category Name</label>
            <select name="category_id" class="form-control" required>
                <option value="">Select Category</option>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ old('category_id') == $cat->id ? 'selected' : '' }}>{{ $cat->name }}</option>
                @endforeach
            </select>
            @error('category_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label>Member ID</label>
            <input type="text" name="member_id" class="form-control" value="{{ old('member_id') }}" required>
            @error('member_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" class="form-control" value="{{ old('email') }}" required>
            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label>Gender</label>
            <select name="gender" class="form-control" required>
                <option value="">Select Gender</option>
                <option {{ old('gender') == 'Male' ? 'selected' : '' }}>Male</option>
                <option {{ old('gender') == 'Female' ? 'selected' : '' }}>Female</option>
                <option {{ old('gender') == 'Other' ? 'selected' : '' }}>Other</option>
            </select>
            @error('gender') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label>Contact Number</label>
            <input type="text" name="contact" class="form-control" value="{{ old('contact') }}" required
                   pattern="^[0-9]{10}$"
                   title="Please enter a valid 10-digit phone number (digits only)">
            @error('contact') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label>Experience</label>
            <input type="text" name="experience" class="form-control" value="{{ old('experience') }}">
        </div>

        <div class="form-group">
            <label>Date of Birth</label>
            <input type="date" name="dob" class="form-control" value="{{ old('dob') }}" required>
            @error('dob') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label>Address</label>
            <textarea name="address" class="form-control" required>{{ old('address') }}</textarea>
            @error('address') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label>Description (optional)</label>
            <textarea name="description" class="form-control">{{ old('description') }}</textarea>
        </div>

        <div class="form-group">
            <label>Member Picture</label>
            <input type="file" name="member_pic" class="form-control-file">
        </div>

        <div class="form-group">
            <label>Resume</label>
            <input type="file" name="resume" class="form-control-file">
        </div>

        <button type="submit" class="btn btn-success">Add Team Member</button>
    </form>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const today = new Date().toISOString().split('T')[0];
        const dobInput = document.querySelector('input[name="dob"]');
        if (dobInput) {
            dobInput.setAttribute('max', today);
        }
    });
</script>
@endsection
