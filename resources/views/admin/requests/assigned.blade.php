@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4" style="font-weight: 600;">{{ $title ?? 'Assigned Requests' }}</h3>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

     <!-- Email filter input -->
     <div class="mb-3">
        <input type="text" id="filterEmail" class="form-control autocomplete-email" placeholder="Search by user email...">
    </div>

    <div class="table-responsive shadow-sm rounded p-3 bg-white">
        <table class="table table-borderless align-middle">
            <thead class="thead-light">
                <tr class="border-bottom">
                    <th>User</th>
                    <th>Service</th>
                    <th>Address</th>
                    <th>Shift</th>
                    <th>Start Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
            @forelse($requests as $req)
                <tr class="border-bottom request-row" data-email="{{ strtolower($req->user->email ?? '') }}" >
                    <td>
                        <strong>{{ $req->user->name ?? '-' }}</strong><br>
                        <small class="text-muted">{{ $req->user->email ?? '-' }}</small>
                    </td>
                    <td>{{ ucfirst($req->category->name ?? '-') }}</td>
                    <td>{{ $req->address }}</td>
                    <td>{{ \Carbon\Carbon::parse($req->work_shift_from)->format('h:i A') }} - {{ \Carbon\Carbon::parse($req->work_shift_to)->format('h:i A') }}</td>
                    <td>{{ \Carbon\Carbon::parse($req->start_date)->format('M d, Y') }}</td>
                    <td class="align-middle">
    <div class="d-flex flex-column align-items-start">
        <span class="badge 
            @if($req->status == 'pending') bg-warning text-dark
            @elseif($req->status == 'completed') bg-success
            @else bg-info
            @endif">
            {{ ucfirst($req->status) }}
        </span>

        <!-- Unassign Form -->
        <form action="{{ route('requests.unassign', $req->id) }}" method="POST" class="mt-2" onsubmit="return confirm('Are you sure you want to unassign this request?');">
            @csrf
            @method('PUT')
            <button type="submit" class="btn btn-sm btn-outline-danger">Unassign</button>
        </form>

        @if($req->teamMember)
            <button class="btn btn-sm btn-link text-primary mt-2 p-0 toggle-details"
                    data-bs-toggle="collapse"
                    data-bs-target="#details{{ $req->id }}"
                    aria-expanded="false"
                    aria-controls="details{{ $req->id }}">
                View More
            </button>
        @endif
    </div>
</td>
                </tr>

                @if($req->teamMember)
                <tr>
                    <td colspan="6" class="p-0">
                        <div id="details{{ $req->id }}" class="collapse">
                            <div class="p-3 bg-light rounded border m-2">
                                <div class="row">
                                    <!-- Left Column: Team Member Info -->
                                    <div class="col-md-6">
                                        <div class="text-center mb-3">
                                            <img src="{{ $req->teamMember->photo_url ?? asset('images/default-avatar.png') }}"
                                                 class="img-thumbnail" alt="Profile Photo" width="120" height="120">
                                        </div>
                                        <h6 class="mb-2">Assigned Team Member:</h6>
                                        <ul class="list-group list-group-flush small">
                                            <li class="list-group-item"><strong>Name:</strong> {{ $req->teamMember->name }}</li>
                                            <li class="list-group-item"><strong>Email:</strong> {{ $req->teamMember->email }}</li>
                                            <li class="list-group-item"><strong>Contact:</strong> {{ $req->teamMember->contact }}</li>
                                            <li class="list-group-item"><strong>Gender:</strong> {{ $req->teamMember->gender }}</li>
                                            <li class="list-group-item"><strong>Experience:</strong> {{ $req->teamMember->experience ?? 'N/A' }}</li>
                                        </ul>

                                       
                                    </div>

                                    <!-- Right Column: Resume -->
                                    <div class="col-md-6">
                                        <h6 class="mb-2">Resume / ID Proof:</h6>
                                        @if($req->teamMember->resume_url)
                                            @php
                                                $isPdf = Str::endsWith(Str::lower(parse_url($req->teamMember->resume_url, PHP_URL_PATH)), '.pdf');
                                            @endphp

                                            @if($isPdf)
                                                <iframe src="{{ $req->teamMember->resume_url }}" width="100%" height="300px" frameborder="0"></iframe>
                                            @else
                                                <img src="{{ $req->teamMember->resume_url }}" class="img-fluid rounded shadow-sm" alt="Resume or ID Proof">
                                            @endif
                                        @else
                                            <p class="text-muted">No resume uploaded.</p>
                                        @endif
                                    </div>
                                </div>
                            </div>
                        </div>
                    </td>
                </tr>
                @endif
            @empty
                <tr><td colspan="6" class="text-center text-muted">No data available</td></tr>
            @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        document.querySelectorAll('.toggle-details').forEach(function (btn) {
            const targetId = btn.getAttribute('data-bs-target');
            const targetEl = document.querySelector(targetId);

            // Change button text based on show/hide
            targetEl.addEventListener('show.bs.collapse', function () {
                btn.textContent = 'Hide';
            });

            targetEl.addEventListener('hide.bs.collapse', function () {
                btn.textContent = 'View More';
            });
        });
    });
</script>

<script>
    $(document).ready(function () {
        // Autocomplete for all email inputs
        $('.autocomplete-email').autocomplete({
            source: '{{ route("team-members.search") }}',
            minLength: 1
        });

        // Filtering table rows by email
        $('#filterEmail').on('keyup', function () {
            const keyword = $(this).val().toLowerCase();

            $('.request-row').each(function () {
                const email = $(this).data('email');
                if (email.includes(keyword)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        });
    });
</script>
@endsection
