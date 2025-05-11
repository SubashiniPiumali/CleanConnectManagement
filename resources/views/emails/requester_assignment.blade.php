<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>Request Assigned</title>
</head>
<body>
    <h2>Hello {{ $request->user->name }},</h2>
    <p>Your request has been assigned to: {{ $teamMember->name }} ({{ $teamMember->email }})</p>

    <ul>
        <li><strong>Team Member Name:</strong> {{ $teamMember->name }}</li>
        <li><strong>Email:</strong> {{ $teamMember->email }}</li>
        <li><strong>Gender:</strong> {{ $teamMember->gender }}</li>
        <li><strong>Contact:</strong> {{ $teamMember->contact }}</li>

        <li><strong>Service:</strong> {{ ucfirst(str_replace('_', ' ', $request->category->name)) }}</li>
        <li><strong>Address:</strong> {{ $request->address }}</li>
        <li><strong>Shift:</strong> {{ \Carbon\Carbon::parse($request->work_shift_from)->format('h:i A') }} - {{ \Carbon\Carbon::parse($request->work_shift_to)->format('h:i A') }}</li>
        <li><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($request->start_date)->format('M d, Y') }}</li>
    </ul>

    <p>You will be contacted shortly. Thank you for choosing our service!</p>
</body>
</html>
