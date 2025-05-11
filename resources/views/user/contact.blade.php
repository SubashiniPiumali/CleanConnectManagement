@extends('layouts.app')

@section('content')
<div class="container py-5">
        <h2 >Contact Us</h2>

        <div class="row">
            <div class="col-md-6">
                <h5>Contact Information</h5>
                <p><strong>Address:</strong> 890, Sector 62, Gyan Sarovar, GAIL Noida (Delhi/NCR)</p>
                <p><strong>Phone:</strong> 7896541239</p>
                <p><strong>Email:</strong> <a href="mailto:info@gmail.com">info@gmail.com</a></p>

                <h5 class="mt-4">Follow Us</h5>
                <a href="#" class="me-2 text-dark"><i class="fab fa-facebook-f"></i></a>
                <a href="#" class="me-2 text-dark"><i class="fab fa-twitter"></i></a>
                <a href="#" class="text-dark"><i class="fab fa-linkedin-in"></i></a>
            </div>

            <div class="col-md-6">
                <h5>Send a Message</h5>
                <form>
                    <div class="mb-3">
                        <label for="name" class="form-label">Your Name</label>
                        <input type="text" class="form-control" id="name" placeholder="Enter your full name">
                    </div>
                    <div class="mb-3">
                        <label for="email" class="form-label">Your Email</label>
                        <input type="email" class="form-control" id="email" placeholder="Enter your email address">
                    </div>
                    <div class="mb-3">
                        <label for="message" class="form-label">Your Message</label>
                        <textarea class="form-control" id="message" rows="4" placeholder="Type your message here"></textarea>
                    </div>
                    <button type="submit" class="btn btn-primary">Send Message</button>
                </form>
            </div>
    </div>
</div>
@endsection
