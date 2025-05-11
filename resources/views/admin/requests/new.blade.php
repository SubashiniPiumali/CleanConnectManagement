@extends('layouts.admin')

@section('content')
<div class="container-fluid">
    <h3 class="mb-4" style="font-weight: 600;">{{ $title ?? ' New Requests' }}</h3>

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
                    <th>Note</th>
                    <th>Status</th>
                    <th>Action</th> 
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $req)
                    <tr class="border-bottom requirement-row" data-email="{{ strtolower($req->user->email ?? '') }}">
                        <td>
                            <strong>{{ $req->user->name ?? '-' }}</strong><br>
                            <small class="text-muted">{{ $req->user->email ?? '-' }}</small>
                        </td>
                        <td>{{ ucfirst(str_replace('_', ' ', $req->category->name)) }}</td>
                        <td>{{ $req->address }}</td>
                        <td>
                            {{ \Carbon\Carbon::parse($req->work_shift_from)->format('h:i A') }}
                            -
                            {{ \Carbon\Carbon::parse($req->work_shift_to)->format('h:i A') }}
                        </td>
                        <td>{{ \Carbon\Carbon::parse($req->start_date)->format('M d, Y') }}</td>
                        <td>{{ $req->notes ?? '-'  }}</td>
                        <td>
                            <span class="badge 
                                @if($req->status == 'pending') bg-warning text-dark
                                @elseif($req->status == 'completed') bg-success
                                @else bg-danger
                                @endif">
                                {{ ucfirst($req->status) }}
                            </span>
                        </td>
                        <td>
                            @if($req->status === 'pending')
                                <!-- Trigger Modal -->
                                <button type="button" class="btn btn-outline-primary btn-sm" data-bs-toggle="modal" data-bs-target="#assignModal{{ $req->id }}">
                                    Approve
                                </button>

                                <!-- Cancel Button -->
                                <button class="btn btn-outline-danger btn-sm" data-bs-toggle="modal" data-bs-target="#cancelModal{{ $req->id }}">
                                    Cancel
                                </button>

                                <!-- Assign Modal -->
                                <div class="modal fade" id="assignModal{{ $req->id }}" tabindex="-1" aria-labelledby="assignModalLabel{{ $req->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form method="POST" action="{{ route('requests.assignByEmail', $req->id) }}">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Assign Team Member by Email</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <div class="form-group">
                                                        <label for="assign_email_{{ $req->id }}">Team Member Email:</label>
                                                        <input type="text" id="assign_email_{{ $req->id }}" class="form-control autocomplete-email" name="team_member_email" placeholder="Start typing email..." autocomplete="off">
                                                        <small class="text-muted">Search and select an email to assign.</small>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-primary">Assign</button>
                                                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>

                                <!-- Cancel Modal -->
                                <div class="modal fade" id="cancelModal{{ $req->id }}" tabindex="-1" aria-labelledby="cancelModalLabel{{ $req->id }}" aria-hidden="true">
                                    <div class="modal-dialog">
                                        <form action="{{ route('requests.cancel', $req->id) }}" method="POST">
                                            @csrf
                                            @method('PUT')
                                            <div class="modal-content">
                                                <div class="modal-header">
                                                    <h5 class="modal-title">Cancel Request</h5>
                                                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                                                </div>
                                                <div class="modal-body">
                                                    <label for="cancel_reason_{{ $req->id }}">Reason for Cancellation:</label>
                                                    <textarea name="cancel_reason" class="form-control" required placeholder="Enter reason..."></textarea>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="submit" class="btn btn-danger">Confirm Cancel</button>
                                                </div>
                                            </div>
                                        </form>
                                    </div>
                                </div>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="7" class="text-center text-muted py-4">No requests found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
<!-- jQuery & jQuery UI for autocomplete -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://code.jquery.com/ui/1.13.2/jquery-ui.min.js"></script>
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">

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
