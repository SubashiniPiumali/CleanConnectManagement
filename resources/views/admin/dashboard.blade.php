@extends('layouts.admin') <!-- Assuming your sidebar layout is saved as layouts.app -->

@section('content')
<div class="container-fluid">
    <h4 class="mb-4">Dashboard</h4>
    <div class="row">
        <!-- Total Category -->
        <div class="col-sm-6 col-lg-4 mb-4">
            <div class="card shadow text-center">
                <div class="card-body">
                    <i class="fas fa-file-alt fa-2x text-primary mb-2"></i>
                    <h3>{{ $totalCategories }}</h3>
                    <p>Total Category</p>
                    <a href="{{ route('category.manage') }}" class="btn btn-outline-primary">View Details</a>
                </div>
            </div>
        </div>

        <!-- Listed Maids -->
        <div class="col-sm-6 col-lg-4 mb-4">
            <div class="card shadow text-center">
                <div class="card-body">
                    <i class="fas fa-users fa-2x text-warning mb-2"></i>
                    <h3>{{ $totalTeamMembers }}</h3>
                    <p>Listed Team Members</p>
                    <a href="{{ route('team-members.index') }}" class="btn btn-outline-primary ">View Details</a>
                </div>
            </div>
        </div>

        <!-- New Request -->
        <div class="col-sm-6 col-lg-4 mb-4">
            <div class="card shadow text-center">
                <div class="card-body">
                    <i class="fas fa-copy fa-2x text-orange mb-2"></i>
                    <h3>{{ $newRequests }}</h3>
                    <p>New Request</p>
                    <a href="{{ route('admin.requests.new') }}" class="btn btn-outline-primary">View Details</a>
                </div>
            </div>
        </div>

        <!-- Assign Request -->
        <div class="col-sm-6 col-lg-4 mb-4">
            <div class="card shadow text-center">
                <div class="card-body">
                    <i class="fas fa-tasks fa-2x text-teal mb-2"></i>
                    <h3>{{ $assignedRequests }}</h3>
                    <p>Assign Request</p>
                    <a href="{{ route('admin.requests.assigned') }}" class="btn btn-outline-primary ">View Detail</a>
                </div>
            </div>
        </div>

        <!-- Cancelled/Rejected Requests -->
        <div class="col-sm-6 col-lg-4 mb-4">
            <div class="card shadow text-center">
                <div class="card-body">
                    <i class="fas fa-times-circle fa-2x text-danger mb-2"></i>
                    <h3>{{ $canceledRequests }}</h3>
                    <p>Canceled / Rejected Requests</p>
                    <a href="{{ route('admin.requests.rejected') }}" class="btn btn-outline-primary ">View Details</a>
                </div>
            </div>
        </div>

        <!-- Total Request -->
        <div class="col-sm-6 col-lg-4 mb-4">
            <div class="card shadow text-center">
                <div class="card-body">
                    <i class="fas fa-layer-group fa-2x text-purple mb-2"></i>
                    <h3>{{ $totalRequests }}</h3>
                    <p>Total Request</p>
                   <span class="text-muted small">No view available</span> 
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
