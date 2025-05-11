@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2>Edit Team Member</h2>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('team-members.update', $member->id) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')

        <div class="form-group">
            <label>Category Name</label>
            <select name="category_id" class="form-control" required>
                @foreach($categories as $cat)
                    <option value="{{ $cat->id }}" {{ $member->category_id == $cat->id ? 'selected' : '' }}>
                        {{ $cat->name }}
                    </option>
                @endforeach
            </select>
            @error('category_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label>Maid ID</label>
            <input type="text" name="member_id" value="{{ old('member_id', $member->member_id) }}" class="form-control" required>
            @error('member_id') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label>Name</label>
            <input type="text" name="name" value="{{ old('name', $member->name) }}" class="form-control" required>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label>Email</label>
            <input type="email" name="email" value="{{ old('email', $member->email) }}" class="form-control" required>
            @error('email') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label>Gender</label>
            <select name="gender" class="form-control" required>
                <option value="Male" {{ $member->gender == 'Male' ? 'selected' : '' }}>Male</option>
                <option value="Female" {{ $member->gender == 'Female' ? 'selected' : '' }}>Female</option>
                <option value="Other" {{ $member->gender == 'Other' ? 'selected' : '' }}>Other</option>
            </select>
            @error('gender') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label>Contact Number</label>
            <input type="text" name="contact" value="{{ old('contact', $member->contact) }}" class="form-control" required
                   pattern="^[0-9]{10}$"
                   title="Enter a valid 10-digit phone number (digits only)">
            @error('contact') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label>Experience</label>
            <input type="text" name="experience" value="{{ old('experience', $member->experience) }}" class="form-control">
        </div>

        <div class="form-group">
            <label>Date of Birth</label>
            <input type="date" name="dob" value="{{ old('dob', $member->dob) }}" class="form-control" required>
            @error('dob') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label>Address</label>
            <textarea name="address" class="form-control" required>{{ old('address', $member->address) }}</textarea>
            @error('address') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <div class="form-group">
            <label>Description</label>
            <textarea name="description" class="form-control">{{ old('description', $member->description) }}</textarea>
        </div>

        <div class="form-group">
            <label>Member Picture</label><br>
            @if($member->photo_url)
                <img src="{{ $member->photo_url }}" alt="Current Photo" width="120" class="mb-2 d-block">
            @endif
            <input type="file" name="member_pic" class="form-control-file">
            <input type="hidden" name="existing_photo_url" value="{{ $member->photo_url }}">
        </div>

        <div class="form-group">
            <label>Resume</label><br>
            @if($member->resume_url)
                <a href="{{ $member->resume_url }}" target="_blank" class="btn btn-sm btn-outline-primary mb-2">View Resume</a>
            @endif
            <input type="file" name="resume" class="form-control-file">
            <input type="hidden" name="existing_resume_url" value="{{ $member->resume_url }}">
        </div>

        <button type="submit" class="btn btn-primary">Update Team Member</button>
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
