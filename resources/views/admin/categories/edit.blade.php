@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2>Edit Category</h2>

    <form method="POST" action="{{ route('category.update', $category->id) }}">
        @csrf
        <div class="form-group">
            <label for="name">Category Name</label>
            <input type="text" name="name" value="{{ $category->name }}" class="form-control" required>
            @error('name') <small class="text-danger">{{ $message }}</small> @enderror
        </div>

        <button type="submit" class="btn btn-success">Update</button>
        <a href="{{ route('category.manage') }}" class="btn btn-secondary">Cancel</a>
    </form>
</div>
@endsection
