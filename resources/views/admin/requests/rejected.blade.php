@extends('layouts.admin')

@section('content')
<div class="container-fluid">
<h3 class="mb-4" style="font-weight: 600;">{{ $title ?? ' Rejected Requests' }}</h3>
   <!-- Email filter input -->
   <div class="mb-3">
        <input type="text" id="filterEmail" class="form-control autocomplete-email" placeholder="Search by user email...">
    </div>

    <div class="table-responsive shadow-sm rounded bg-white p-3">
        <table class="table table-borderless align-middle">
            <thead class="thead-light">
                <tr class="border-bottom">
                    <th>User</th>
                    <th>Service</th>
                    <th>Address</th>
                    <th>Shift</th>
                    <th>Start Date</th>
                    <th>Status</th>
                    <th>Reason</th>
                </tr>
            </thead>
            <tbody>
                @forelse($requests as $req)
                    <tr class="border-bottom request-row" data-email="{{ strtolower($req->user->email ?? '') }}" >
                        <td>
                            <strong>{{ $req->user->name ?? '-' }}</strong><br>
                            <small class="text-muted">{{ $req->user->email ?? '-' }}</small>
                        </td>
                        <td class="text-capitalize">{{ $req->category->name ?? '-' }}</td>
                        <td>{{ $req->address }}</td>
                        <td>{{ \Carbon\Carbon::parse($req->work_shift_from)->format('h:i A') }} - {{ \Carbon\Carbon::parse($req->work_shift_to)->format('h:i A') }}</td>
                        <td>{{ \Carbon\Carbon::parse($req->start_date)->format('M d, Y') }}</td>
                        <td>
                        <span class="badge 
                                @if($req->status == 'pending') bg-warning text-dark
                                @elseif($req->status == 'completed') bg-success
                                @else bg-danger
                                @endif">
                                {{ ucfirst($req->status) }}
                            </span>
                        </td>
                        <td>{{  $req->cancel_reason  }}</td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-4">No requests found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>
</div>
@endsection

@section('scripts')
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