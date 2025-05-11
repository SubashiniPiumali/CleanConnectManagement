@extends('layouts.app')

@section('content')
    <h1>Welcome to the Clean Connect Management System</h1>
    <p class="custom-paragraph">
    Welcome to the Clean Connect Management System, your trusted platform for finding skilled and trained professionals for all your home cleaning and domestic needs. Our system provides a seamless and efficient way to connect with experienced maids who are prepared to assist you with a variety of tasks, ensuring your home is clean, organized, and well-maintained.
</p>

<p class="custom-paragraph">
    Whether you're looking for regular cleaning, deep cleaning, or specialized maid services, we are committed to providing you with the highest level of service and professionalism. Join us today and experience the convenience of hiring the best domestic help at your fingertips.
</p>

    <!-- Button to Post Request -->
       <!-- Show only for logged-in users with role 'user' -->
    @auth
        @if(Auth::user()->role === 'user')
     <br>
   <!-- Button to Show Form -->
   <button id="postRequestBtn" class="btn btn-primary">Post Request Here</button>

<!-- Hidden Form -->
<div id="requestForm" style="display: none;">
    <h3>Post Your Request</h3>
    <form method="POST" action="{{ route('requests.store') }}">
        @csrf
        <div class="form-group">
            <label for="contact_number">Contact Number:</label>
            <input type="text" name="contact_number" id="contact_number" class="form-control" placeholder="Enter your contact number" required
            pattern="^[0-9]{10}$"
                   title="Please enter a valid 10-digit phone number (digits only)">
        </div>
        <div class="form-group">
            <label for="address">Address (to be hired):</label>
            <textarea name="address" id="address" class="form-control" placeholder="Enter your address" required></textarea>
        </div>
        <div class="form-group">
            <label for="service">Select Service:</label>
            <select name="category_id" id="category_id" class="form-control" required>
                <option value="">Select Service</option>
                @foreach($categories as $category)
                    <option  value="{{ $category->id }}">{{ $category->name }}</option>
                @endforeach
            </select>
        </div>
        <div class="form-group">
            <label for="work_shift_from">Work Shift From:</label>
            <input type="time" name="work_shift_from" id="work_shift_from" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="work_shift_to">Work Shift To:</label>
            <input type="time" name="work_shift_to" id="work_shift_to" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="start_date">Start Date:</label>
            <input type="date" name="start_date" id="start_date" class="form-control" required>
        </div>
        <div class="form-group">
            <label for="notes">Notes:</label>
            <textarea name="notes" id="notes" class="form-control" placeholder="Enter any additional notes"></textarea>
        </div>
        <button type="submit" class="btn btn-primary">Send</button>
    </form>
</div>

@endif
    @endauth
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Get today's date in YYYY-MM-DD format
        const today = new Date().toISOString().split('T')[0];

        // Set min attribute to today
        document.getElementById('start_date').setAttribute('min', today);
    });
</script>

@if(session('success'))
    <script>
        alert("{{ session('success') }}");
    </script>
@endif
@endsection
