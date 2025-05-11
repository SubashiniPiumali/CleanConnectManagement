@if($requests->isEmpty())
    <div class="alert alert-info text-center">No requests found.</div>
@else
<div class="table-responsive shadow-sm rounded">
    <table class="table table-striped table-hover table-sm align-middle">
        <thead class="thead-light">
            <tr>
                <th>Service</th>
                <th>Start Date</th>
                <th>Shift</th>
                <th>Address</th>
                <th>Contact Number</th>
                <th>Note</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            @foreach($requests as $req)
                <tr>
                    <td class="text-capitalize">{{ $req->category->name ?? 'N/A' }}</td>
                    <td>{{ \Carbon\Carbon::parse($req->start_date)->format('M d, Y') }}</td>
                    <td>{{ \Carbon\Carbon::parse($req->work_shift_from)->format('h:i A') }} - {{ \Carbon\Carbon::parse($req->work_shift_to)->format('h:i A') }}</td>
                    <td>{{ $req->address }}</td>
                    <td>{{ $req->contact_number }}</td>
                    <td>{{ $req->notes }}</td>
                  
                    <td>
                    @if($req->status === 'canceled')
                    <span class="text-danger">{{ $req->cancel_reason ?? '' }}</span>

                      @elseif($req->teamMember)
                            <button class="btn btn-sm btn-outline-primary toggle-user-details"
                                data-bs-toggle="collapse"
                                data-bs-target="#user-details-{{ $req->id }}">
                                View Assignee
                            </button>
                        @endif

                        @if($req->status === 'pending')
                            <button class="btn btn-sm btn-warning mt-1" data-bs-toggle="collapse"
                                    data-bs-target="#edit-row-{{ $req->id }}">
                                Edit
                            </button>
                            <form action="{{ route('requests.destroy', $req->id) }}" method="POST" class="d-inline" onsubmit="return confirm('Are you sure you want to delete this request?');">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-sm btn-danger mt-1">Delete</button>
                        </form>
                        @endif
                    </td>
                </tr>

                {{-- 🔽 Team Member Info --}}
                @if($req->teamMember)
                <tr id="user-details-{{ $req->id }}" class="collapse bg-light">
                    <td colspan="6">
                        <div class="row p-3">
                            <div class="col-md-3 text-center">
                                <img src="{{ $req->teamMember->photo_url ?? asset('images/default-avatar.png') }}"
                                    class="img-thumbnail" width="120" height="120" alt="Profile Photo">
                            </div>
                            <div class="col-md-9">
                                <h6>Assigned Team Member</h6>
                                <ul class="list-group list-group-flush small">
                                    <li class="list-group-item"><strong>Name:</strong> {{ $req->teamMember->name }}</li>
                                    <li class="list-group-item"><strong>Email:</strong> {{ $req->teamMember->email }}</li>
                                    <li class="list-group-item"><strong>Contact:</strong> {{ $req->teamMember->contact }}</li>
                                    <li class="list-group-item"><strong>Gender:</strong> {{ $req->teamMember->gender }}</li>
                                    <li class="list-group-item"><strong>Experience:</strong> {{ $req->teamMember->experience ?? 'N/A' }}</li>
                                    @if($req->teamMember->resume_url && Str::endsWith($req->teamMember->resume_url, '.pdf'))
                                        <li class="list-group-item">
                                            <strong>Resume:</strong><br>
                                            <embed src="{{ $req->teamMember->resume_url }}" type="application/pdf" width="100%" height="300px">
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    </td>
                </tr>
                @endif

                {{-- 🔧 Edit Form --}}
                <tr id="edit-row-{{ $req->id }}" class="collapse bg-white">
                    <td colspan="6">
                        <form method="POST" action="{{ route('requests.update', $req->id) }}">
                            @csrf
                            @method('PUT')
                            <div class="row g-3 p-3">
                                <div class="col-md-4">
                                    <label>Start Date</label>
                                    <input type="date" name="start_date" value="{{ $req->start_date }}" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label>Shift From</label>
                                    <input type="time" name="work_shift_from" value="{{ $req->work_shift_from }}" class="form-control" required>
                                </div>
                                <div class="col-md-4">
                                    <label>Shift To</label>
                                    <input type="time" name="work_shift_to" value="{{ $req->work_shift_to }}" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label>Contact Number</label>
                                    <input type="text" name="contact_number" value="{{ $req->contact_number }}" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                    <label>Address</label>
                                    <input type="text" name="address" value="{{ $req->address }}" class="form-control" required>
                                </div>
                                <div class="col-md-6">
                                <label>Category</label>
                                <select name="category_id" class="form-control" required>
                                    @foreach($categories as $cat)
                                        <option value="{{ $cat->id }}" {{ $cat->id == $req->category_id ? 'selected' : '' }}>
                                            {{ $cat->name }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                                <div class="col-12">
                                    <label>Notes</label>
                                    <textarea name="notes" class="form-control">{{ $req->notes }}</textarea>
                                </div>
                                <div class="col-12 text-end mt-2">
                                    <button type="submit" class="btn btn-success btn-sm">Save Changes</button>
                                </div>
                            </div>
                        </form>
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
@endif

@section('scripts')
<script>
    document.addEventListener('DOMContentLoaded', function () {
        // Set today's date in YYYY-MM-DD format
        const today = new Date().toISOString().split('T')[0];

        // Set min attribute on all visible or dynamically toggled start_date inputs
        document.querySelectorAll('input[name="start_date"]').forEach(function(input) {
            input.setAttribute('min', today);
        });

        // Toggle team member details
        document.querySelectorAll('.toggle-user-details').forEach(function (btn) {
            btn.addEventListener('click', function () {
                const targetId = btn.getAttribute('data-bs-target');
                const targetEl = document.querySelector(targetId);
                const collapse = bootstrap.Collapse.getOrCreateInstance(targetEl);
                collapse.toggle();

                setTimeout(() => {
                    btn.textContent = targetEl.classList.contains('show') ? 'Hide Details' : 'View Assignee';
                }, 350);
            });
        });
    });
</script>
@endsection
