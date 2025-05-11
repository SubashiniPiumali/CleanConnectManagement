@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h2 class="mb-4">Team Members</h2>

    
    @if(session('error'))
        <div class="alert alert-warning">{{ session('error') }}</div>
    @endif


    <!-- Search -->
    <div class="row mb-3">
        <div class="col-md-6">
            <input type="text" id="searchInput" class="form-control" placeholder="Search by name or email..." onkeydown="return event.key !== 'Enter';">
        </div>
    </div>

    <div class="row" id="teamMemberCards">
        @foreach($members as $member)
        <div class="col-md-4 d-flex align-items-stretch member-card">
            <div class="card shadow-sm mb-4 w-100">
                <div class="card-body position-relative">
        <!-- Category Badge -->
@if($member->category)
    <span class="badge bg-primary position-absolute top-0 end-0 m-2" style="z-index: 1;">
        {{ $member->category->name }}
    </span>
@endif

<!-- Edit/Delete Icons (pushed down) -->
<div class="position-absolute top-0 end-0 mt-5 me-2 d-flex gap-2" >
    <a href="{{ route('team-members.edit', $member->id) }}" class="text-primary" title="Edit">
        <i class="fas fa-edit fa-lg"></i>
    </a>
    <form action="{{ route('team-members.destroy', $member->id) }}" method="POST" onsubmit="return confirm('Are you sure you want to delete this member?');">
        @csrf
        @method('DELETE')
        <button type="submit" class="btn btn-link text-danger p-0 m-0" title="Delete">
            <i class="fas fa-trash-alt fa-lg"></i>
        </button>
    </form>
</div>

                    <div class="d-flex align-items-center mb-3">
                        <img src="{{ $member->photo_url ?? asset('images/default-avatar.png') }}"
                             class="rounded-circle me-3" width="50" height="50" alt="Profile">
                        <div>
                            <h5 class="mb-0">{{ $member->name }}</h5>
                            <small class="text-muted">{{ $member->email }}</small>
                        </div>
                    </div>

                    <p><strong>Experience:</strong> {{ $member->experience }}</p>
                    <button type="button" class="btn btn-outline-primary btn-block" data-bs-toggle="modal" data-bs-target="#memberModal{{ $member->id }}">
                        View More
                    </button>
                </div>
            </div>
        </div>

        <!-- Modal -->
        <div class="modal fade" id="memberModal{{ $member->id }}" tabindex="-1" aria-labelledby="memberModalLabel{{ $member->id }}" aria-hidden="true">
            <div class="modal-dialog modal-xl modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="memberModalLabel{{ $member->id }}">{{ $member->name }}'s Details</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <!-- Left Side -->
                            <div class="col-md-7">
                                <div class="d-flex align-items-center mb-3">
                                    <img src="{{ $member->photo_url ?? asset('images/default-avatar.png') }}"
                                         alt="Profile Picture" class="rounded-circle me-3" width="70" height="70">
                                    <div>
                                        <h4 class="mb-0">{{ $member->name }}</h4>
                                        <small class="text-muted">{{ $member->email }}</small>
                                    </div>
                                </div>

                                <ul class="list-group mb-3">
                                    <li class="list-group-item"><strong>Email:</strong> {{ $member->email }}</li>
                                    <li class="list-group-item"><strong>Gender:</strong> {{ $member->gender }}</li>
                                    <li class="list-group-item"><strong>Contact:</strong> {{ $member->contact }}</li>
                                    <li class="list-group-item"><strong>DOB:</strong> {{ $member->dob }}</li>
                                    <li class="list-group-item"><strong>Experience:</strong> {{ $member->experience }}</li>
                                    <li class="list-group-item"><strong>Category:</strong> {{ $member->category->name ?? 'N/A' }}</li>
                                    <li class="list-group-item"><strong>Address:</strong> {{ $member->address }}</li>
                                    <li class="list-group-item"><strong>Description:</strong> {{ $member->description ?? 'N/A' }}</li>
                                </ul>
                                @if($member->requests->isNotEmpty())
                                    @php
                                        $events = $member->requests->map(function ($req) {
                                            return [
                                                "title" => ucfirst($req->status),
                                                "start" => $req->start_date . "T" . $req->work_shift_from,
                                                "end" => $req->start_date . "T" . $req->work_shift_to,
                                                "color" => $req->status === "assigned" ? "#198754" : "#ffc107",
                                            ];
                                        })->values()->all();
                                    @endphp
                                    <div class="calendar-container mb-3"
                                         style="min-height: 400px;"
                                         data-events='@json($events)'>
                                    </div>
                                @else
                                    <p class="text-muted">No assigned requests.</p>
                                @endif
                            </div>

                            <!-- Right Side (Resume) -->
                            <div class="col-md-5">
                                <h6>Resume</h6>
                                @if($member->resume_url)
                                    @php
                                        $isPdf = Str::endsWith(Str::lower(parse_url($member->resume_url, PHP_URL_PATH)), '.pdf');
                                    @endphp

                                    @if($isPdf)
                                        <iframe src="{{ $member->resume_url }}" width="100%" height="750px" frameborder="0"></iframe>
                                    @else
                                        <img src="{{ $member->resume_url }}" class="img-fluid" alt="Resume or ID Proof">
                                    @endif

                                    <a href="{{ $member->resume_url }}" class="btn btn-outline-success btn-block mt-3" target="_blank">Download</a>
                                @else
                                    <p class="text-muted">No resume uploaded.</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        @endforeach
    </div>

    <p id="noResults" class="text-muted mt-3 text-center" style="display: none;">No members found.</p>
</div>

<!-- Search Filter Script -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const searchInput = document.getElementById('searchInput');
        const noResults = document.getElementById('noResults');

        searchInput.addEventListener('input', function () {
            const search = this.value.toLowerCase();
            const cards = document.querySelectorAll('.member-card');
            let matchFound = false;

            cards.forEach(function (card) {
                const name = card.querySelector('h5')?.textContent.toLowerCase() || '';
                const email = card.querySelector('small')?.textContent.toLowerCase() || '';
                const matched = name.includes(search) || email.includes(search);

                card.classList.toggle('d-none', !matched);
                if (matched) matchFound = true;
            });

            noResults.style.display = matchFound ? 'none' : 'block';
        });
    });
</script>

<!-- FullCalendar Render Script -->
<script>
    document.addEventListener('DOMContentLoaded', function () {
        const modals = document.querySelectorAll('.modal');

        modals.forEach(modal => {
            modal.addEventListener('shown.bs.modal', function () {
                const calendarEl = modal.querySelector('.calendar-container');

                if (calendarEl && !calendarEl.classList.contains('initialized')) {
                    const eventsData = JSON.parse(calendarEl.dataset.events);

                    const calendar = new FullCalendar.Calendar(calendarEl, {
                        initialView: 'timeGridWeek',
                        headerToolbar: {
                            left: 'prev,next today',
                            center: 'title',
                            right: 'timeGridWeek,timeGridDay'
                        },
                        slotMinTime: "06:00:00",
                        slotMaxTime: "22:00:00",
                        allDaySlot: false,
                        height: 400,
                        events: eventsData
                    });

                    calendar.render();
                    calendarEl.classList.add('initialized');
                }
            });
        });
    });
</script>
@endsection
