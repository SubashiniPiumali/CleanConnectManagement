@extends('layouts.app')

@section('content')
<div class="container">
    <h2>Complete Your Profile</h2>
    <form method="POST" action="{{ route('user.profile.update') }}">
    @csrf
    @method('PUT')

    <div class="form-group">
        <label>Name</label>
        <input type="text" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
    </div>

    <div class="form-group mt-2">
        <label>Email</label>
        <input type="email" name="email" class="form-control" value="{{ old('email', $user->email) }}" readonly>
    </div>

    <div class="form-group mt-2">
        <label>Gender</label>
        <select name="gender" class="form-control" required>
            <option value="">--Select--</option>
            <option value="male" {{ old('gender', $user->gender) == 'male' ? 'selected' : '' }}>Male</option>
            <option value="female" {{ old('gender', $user->gender) == 'female' ? 'selected' : '' }}>Female</option>
        </select>
    </div>

    <button class="btn btn-primary mt-3" type="submit">Update Profile</button>
</form>
</div>
@endsection
