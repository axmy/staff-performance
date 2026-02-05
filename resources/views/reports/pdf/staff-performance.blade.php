<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Staff Performance Report - {{ $staff->name }}</title>
    <style>
        body { font-family: Arial, sans-serif; font-size: 12px; line-height: 1.4; }
        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #4f46e5; padding-bottom: 20px; }
        .header h1 { color: #4f46e5; margin: 0; }
        .header p { color: #6b7280; margin: 5px 0 0 0; }
        .section { margin-bottom: 25px; }
        .section-title { font-size: 14px; font-weight: bold; color: #374151; margin-bottom: 10px; border-bottom: 1px solid #e5e7eb; padding-bottom: 5px; }
        table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        th, td { padding: 8px; text-align: left; border: 1px solid #e5e7eb; }
        th { background-color: #f3f4f6; font-weight: bold; }
        .info-grid { display: table; width: 100%; }
        .info-row { display: table-row; }
        .info-label { display: table-cell; width: 30%; padding: 5px; font-weight: bold; color: #6b7280; }
        .info-value { display: table-cell; padding: 5px; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 10px; }
        .badge-green { background-color: #d1fae5; color: #065f46; }
        .badge-red { background-color: #fee2e2; color: #991b1b; }
        .badge-yellow { background-color: #fef3c7; color: #92400e; }
        .badge-blue { background-color: #dbeafe; color: #1e40af; }
        .footer { margin-top: 30px; text-align: center; font-size: 10px; color: #9ca3af; border-top: 1px solid #e5e7eb; padding-top: 10px; }
    </style>
</head>
<body>
    <div class="header">
        <h1>Staff Performance Report</h1>
        <p>{{ $staff->name }} ({{ $staff->record_card_number }}) - Year {{ $year }}</p>
    </div>

    <div class="section">
        <div class="section-title">Staff Information</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Name:</div>
                <div class="info-value">{{ $staff->name }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Record Card No:</div>
                <div class="info-value">{{ $staff->record_card_number }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Department:</div>
                <div class="info-value">{{ $staff->department?->name ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Designation:</div>
                <div class="info-value">{{ $staff->designation?->name ?? '-' }}</div>
            </div>
            <div class="info-row">
                <div class="info-label">Status:</div>
                <div class="info-value">{{ ucfirst($staff->status) }}</div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Monthly Attendance Summary</div>
        @if($staff->monthlyAttendances->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Month</th>
                    <th>Present</th>
                    <th>Absent</th>
                    <th>Late</th>
                    <th>Leave</th>
                    <th>Working Days</th>
                    <th>Rate</th>
                </tr>
            </thead>
            <tbody>
                @foreach($staff->monthlyAttendances as $attendance)
                <tr>
                    <td>{{ $attendance->month_name }}</td>
                    <td>{{ $attendance->days_present }}</td>
                    <td>{{ $attendance->days_absent }}</td>
                    <td>{{ $attendance->days_late }}</td>
                    <td>{{ $attendance->days_leave }}</td>
                    <td>{{ $attendance->total_working_days }}</td>
                    <td>{{ $attendance->attendance_percentage }}%</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p>No attendance records found for this period.</p>
        @endif
    </div>

    <div class="section">
        <div class="section-title">Training Attendance</div>
        @if($staff->trainingAttendances->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Training</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                @foreach($staff->trainingAttendances as $training)
                <tr>
                    <td>{{ $training->trainingSession->title }}</td>
                    <td>{{ $training->trainingSession->scheduled_date->format('d M Y') }}</td>
                    <td>
                        <span class="badge
                            @if($training->attendance_status === 'present') badge-green
                            @elseif($training->attendance_status === 'late') badge-yellow
                            @elseif($training->attendance_status === 'absent') badge-red
                            @else badge-blue @endif">
                            {{ ucfirst($training->attendance_status) }}
                        </span>
                    </td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p>No training records found for this period.</p>
        @endif
    </div>

    <div class="section">
        <div class="section-title">Actions Taken</div>
        @if($staff->actions->count() > 0)
        <table>
            <thead>
                <tr>
                    <th>Action Type</th>
                    <th>Period</th>
                    <th>Status</th>
                    <th>Created</th>
                </tr>
            </thead>
            <tbody>
                @foreach($staff->actions as $action)
                <tr>
                    <td>{{ $action->actionType->name }}</td>
                    <td>{{ $action->period }}</td>
                    <td>
                        <span class="badge
                            @if($action->status === 'completed') badge-green
                            @elseif($action->status === 'pending') badge-yellow
                            @else badge-blue @endif">
                            {{ ucfirst($action->status) }}
                        </span>
                    </td>
                    <td>{{ $action->created_at->format('d M Y') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        @else
        <p>No actions recorded for this period.</p>
        @endif
    </div>

    <div class="footer">
        <p>Generated on {{ now()->format('d M Y H:i') }} | Staff Performance Management System</p>
    </div>
</body>
</html>
