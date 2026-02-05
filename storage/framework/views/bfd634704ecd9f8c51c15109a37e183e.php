<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Staff Performance Report - <?php echo e($staff->name); ?></title>
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
        <p><?php echo e($staff->name); ?> (<?php echo e($staff->record_card_number); ?>) - Year <?php echo e($year); ?></p>
    </div>

    <div class="section">
        <div class="section-title">Staff Information</div>
        <div class="info-grid">
            <div class="info-row">
                <div class="info-label">Name:</div>
                <div class="info-value"><?php echo e($staff->name); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Record Card No:</div>
                <div class="info-value"><?php echo e($staff->record_card_number); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Department:</div>
                <div class="info-value"><?php echo e($staff->department?->name ?? '-'); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Designation:</div>
                <div class="info-value"><?php echo e($staff->designation?->name ?? '-'); ?></div>
            </div>
            <div class="info-row">
                <div class="info-label">Status:</div>
                <div class="info-value"><?php echo e(ucfirst($staff->status)); ?></div>
            </div>
        </div>
    </div>

    <div class="section">
        <div class="section-title">Monthly Attendance Summary</div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($staff->monthlyAttendances->count() > 0): ?>
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
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $staff->monthlyAttendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($attendance->month_name); ?></td>
                    <td><?php echo e($attendance->days_present); ?></td>
                    <td><?php echo e($attendance->days_absent); ?></td>
                    <td><?php echo e($attendance->days_late); ?></td>
                    <td><?php echo e($attendance->days_leave); ?></td>
                    <td><?php echo e($attendance->total_working_days); ?></td>
                    <td><?php echo e($attendance->attendance_percentage); ?>%</td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p>No attendance records found for this period.</p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <div class="section">
        <div class="section-title">Training Attendance</div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($staff->trainingAttendances->count() > 0): ?>
        <table>
            <thead>
                <tr>
                    <th>Training</th>
                    <th>Date</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $staff->trainingAttendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $training): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($training->trainingSession->title); ?></td>
                    <td><?php echo e($training->trainingSession->scheduled_date->format('d M Y')); ?></td>
                    <td>
                        <span class="badge
                            <?php if($training->attendance_status === 'present'): ?> badge-green
                            <?php elseif($training->attendance_status === 'late'): ?> badge-yellow
                            <?php elseif($training->attendance_status === 'absent'): ?> badge-red
                            <?php else: ?> badge-blue <?php endif; ?>">
                            <?php echo e(ucfirst($training->attendance_status)); ?>

                        </span>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p>No training records found for this period.</p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <div class="section">
        <div class="section-title">Actions Taken</div>
        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($staff->actions->count() > 0): ?>
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
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $staff->actions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                <tr>
                    <td><?php echo e($action->actionType->name); ?></td>
                    <td><?php echo e($action->period); ?></td>
                    <td>
                        <span class="badge
                            <?php if($action->status === 'completed'): ?> badge-green
                            <?php elseif($action->status === 'pending'): ?> badge-yellow
                            <?php else: ?> badge-blue <?php endif; ?>">
                            <?php echo e(ucfirst($action->status)); ?>

                        </span>
                    </td>
                    <td><?php echo e($action->created_at->format('d M Y')); ?></td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
        <?php else: ?>
        <p>No actions recorded for this period.</p>
        <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
    </div>

    <div class="footer">
        <p>Generated on <?php echo e(now()->format('d M Y H:i')); ?> | Staff Performance Management System</p>
    </div>
</body>
</html>
<?php /**PATH /Users/axmeen/Projects/staff-performance/resources/views/reports/pdf/staff-performance.blade.php ENDPATH**/ ?>