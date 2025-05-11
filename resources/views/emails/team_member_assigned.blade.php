<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>New Assignment</title>
</head>
<body>
    <<h2>Hello {{ $teamMember->name }},</h2>
    <p>You’ve been assigned a new service request:</p>

    <ul>
        <li><strong>Service:</strong> {{ ucfirst(str_replace('_', ' ', $request->category->name)) }}</li>
        <li><strong>Address:</strong> {{ $request->address }}</li>
        <li><strong>Shift:</strong> {{ \Carbon\Carbon::parse($request->work_shift_from)->format('h:i A') }} - {{ \Carbon\Carbon::parse($request->work_shift_to)->format('h:i A') }}</li>
        <li><strong>Start Date:</strong> {{ \Carbon\Carbon::parse($request->start_date)->format('M d, Y') }}</li>
        <li><strong>Notes:</strong> {{ $request->notes ?? 'N/A' }}</li>
    </ul>

    <p>Please prepare accordingly. Thank you!</p>
</body>
</html>