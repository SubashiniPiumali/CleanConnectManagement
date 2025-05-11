@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="bg-white p-4 rounded shadow-sm">
        <h3 class="mb-4">Your Requests</h3>

        <ul class="nav nav-tabs" id="requestTabs" role="tablist">
            <li class="nav-item">
                <a class="nav-link active" data-toggle="tab" href="#pending">Pending</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#assigned">Assigned</a>
            </li>
            <li class="nav-item">
                <a class="nav-link" data-toggle="tab" href="#canceled">Canceled</a>
            </li>
        </ul>

        <div class="tab-content mt-3">
            <!-- Pending -->
            <div class="tab-pane fade show active" id="pending">
                @include('user.requests.partials.table', ['requests' => $pending])
            </div>

            <!-- Assigned -->
            <div class="tab-pane fade" id="assigned">
                @include('user.requests.partials.table', ['requests' => $assigned])
            </div>

            <!-- Canceled -->
            <div class="tab-pane fade" id="canceled">
                @include('user.requests.partials.table', ['requests' => $canceled])
            </div>
        </div>
    </div>
</div>
@endsection
