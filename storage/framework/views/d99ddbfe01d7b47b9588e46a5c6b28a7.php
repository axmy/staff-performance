<?php if (isset($component)) { $__componentOriginal5863877a5171c196453bfa0bd807e410 = $component; } ?>
<?php if (isset($attributes)) { $__attributesOriginal5863877a5171c196453bfa0bd807e410 = $attributes; } ?>
<?php $component = Illuminate\View\AnonymousComponent::resolve(['view' => 'components.layouts.app','data' => []] + (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag ? $attributes->all() : [])); ?>
<?php $component->withName('layouts.app'); ?>
<?php if ($component->shouldRender()): ?>
<?php $__env->startComponent($component->resolveView(), $component->data()); ?>
<?php if (isset($attributes) && $attributes instanceof Illuminate\View\ComponentAttributeBag): ?>
<?php $attributes = $attributes->except(\Illuminate\View\AnonymousComponent::ignoredParameterNames()); ?>
<?php endif; ?>
<?php $component->withAttributes([]); ?>
     <?php $__env->slot('title', null, []); ?> <?php echo e($staff->name); ?> - History <?php $__env->endSlot(); ?>

    
    <div class="mb-8">
        <a href="<?php echo e(route('staff.show', $staff)); ?>"
           class="inline-flex items-center gap-1.5 text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors duration-150">
            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" d="M10.5 19.5 3 12m0 0 7.5-7.5M3 12h18" />
            </svg>
            Back to Staff Details
        </a>

        <div class="mt-4 flex flex-col sm:flex-row sm:items-end sm:justify-between gap-4">
            <div>
                <h1 class="text-2xl font-bold tracking-tight text-gray-900"><?php echo e($staff->name); ?> - Performance History</h1>
                <p class="mt-1 text-sm text-gray-500 font-mono"><?php echo e($staff->record_card_number); ?></p>
            </div>
            <a href="<?php echo e(route('reports.pdf.staff', ['staff' => $staff, 'year' => now()->year])); ?>"
               class="inline-flex items-center gap-2 px-6 py-3 rounded-lg font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 shadow-sm hover:shadow-md transition-all duration-200 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-sm">
                <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M3 16.5v2.25A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75V16.5M16.5 12 12 16.5m0 0L7.5 12m4.5 4.5V3" />
                </svg>
                Download PDF Report
            </a>
        </div>
    </div>

    
    <div class="bg-white shadow-sm rounded-xl border border-gray-100 mb-8">
        <div class="px-6 py-5 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <span class="flex items-center justify-center w-9 h-9 rounded-lg bg-green-50">
                    <svg class="w-5 h-5 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                    </svg>
                </span>
                <h3 class="text-base font-semibold text-gray-900">Attendance History</h3>
            </div>
        </div>

        <div class="p-6">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($staff->monthlyAttendances->count() > 0): ?>
            <div class="overflow-x-auto -mx-6 px-6">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="pb-3 pr-6 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Period</th>
                            <th class="pb-3 px-4 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">Present</th>
                            <th class="pb-3 px-4 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">Absent</th>
                            <th class="pb-3 px-4 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">Late</th>
                            <th class="pb-3 px-4 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">Leave</th>
                            <th class="pb-3 px-4 text-center text-xs font-semibold uppercase tracking-wider text-gray-500">Working Days</th>
                            <th class="pb-3 pl-6 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Rate</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $staff->monthlyAttendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 pr-6 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900"><?php echo e($attendance->period); ?></span>
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center justify-center min-w-[2rem] px-2.5 py-0.5 text-xs font-semibold rounded-full bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/10">
                                    <?php echo e($attendance->days_present); ?>

                                </span>
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center justify-center min-w-[2rem] px-2.5 py-0.5 text-xs font-semibold rounded-full bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/10">
                                    <?php echo e($attendance->days_absent); ?>

                                </span>
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center justify-center min-w-[2rem] px-2.5 py-0.5 text-xs font-semibold rounded-full bg-yellow-50 text-yellow-700 ring-1 ring-inset ring-yellow-600/10">
                                    <?php echo e($attendance->days_late); ?>

                                </span>
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap text-center">
                                <span class="inline-flex items-center justify-center min-w-[2rem] px-2.5 py-0.5 text-xs font-semibold rounded-full bg-blue-50 text-blue-700 ring-1 ring-inset ring-blue-600/10">
                                    <?php echo e($attendance->days_leave); ?>

                                </span>
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap text-center">
                                <span class="text-sm text-gray-600 font-medium"><?php echo e($attendance->total_working_days); ?></span>
                            </td>
                            <td class="py-4 pl-6 whitespace-nowrap">
                                <div class="flex items-center gap-3">
                                    <span class="text-sm font-semibold tabular-nums
                                        <?php if($attendance->attendance_percentage >= 90): ?> text-green-700
                                        <?php elseif($attendance->attendance_percentage >= 75): ?> text-yellow-700
                                        <?php else: ?> text-red-700 <?php endif; ?>">
                                        <?php echo e($attendance->attendance_percentage); ?>%
                                    </span>
                                    <div class="w-20 bg-gray-200 rounded-full h-2 overflow-hidden">
                                        <div class="h-2 rounded-full transition-all duration-300
                                            <?php if($attendance->attendance_percentage >= 90): ?> bg-green-500
                                            <?php elseif($attendance->attendance_percentage >= 75): ?> bg-yellow-500
                                            <?php else: ?> bg-red-500 <?php endif; ?>"
                                            style="width: <?php echo e(min($attendance->attendance_percentage, 100)); ?>%">
                                        </div>
                                    </div>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="text-center py-10">
                <svg class="mx-auto h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M6.75 3v2.25M17.25 3v2.25M3 18.75V7.5a2.25 2.25 0 0 1 2.25-2.25h13.5A2.25 2.25 0 0 1 21 7.5v11.25m-18 0A2.25 2.25 0 0 0 5.25 21h13.5A2.25 2.25 0 0 0 21 18.75m-18 0v-7.5A2.25 2.25 0 0 1 5.25 9h13.5A2.25 2.25 0 0 1 21 11.25v7.5" />
                </svg>
                <p class="mt-3 text-sm text-gray-500">No attendance records found.</p>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    
    <div class="bg-white shadow-sm rounded-xl border border-gray-100 mb-8">
        <div class="px-6 py-5 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <span class="flex items-center justify-center w-9 h-9 rounded-lg bg-blue-50">
                    <svg class="w-5 h-5 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                    </svg>
                </span>
                <h3 class="text-base font-semibold text-gray-900">Training History</h3>
            </div>
        </div>

        <div class="p-6">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($staff->trainingAttendances->count() > 0): ?>
            <div class="overflow-x-auto -mx-6 px-6">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="pb-3 pr-6 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Training</th>
                            <th class="pb-3 px-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Date</th>
                            <th class="pb-3 px-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                            <th class="pb-3 pl-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Reason</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $staff->trainingAttendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $training): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 pr-6 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900"><?php echo e($training->trainingSession->title); ?></span>
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600"><?php echo e($training->trainingSession->scheduled_date->format('d M Y')); ?></span>
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold rounded-full <?php echo e($training->status_badge_class); ?>">
                                    <?php echo e($training->status_label); ?>

                                </span>
                            </td>
                            <td class="py-4 pl-4">
                                <span class="text-sm text-gray-500"><?php echo e($training->absence_reason ?? '-'); ?></span>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="text-center py-10">
                <svg class="mx-auto h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M4.26 10.147a60.438 60.438 0 0 0-.491 6.347A48.62 48.62 0 0 1 12 20.904a48.62 48.62 0 0 1 8.232-4.41 60.46 60.46 0 0 0-.491-6.347m-15.482 0a50.636 50.636 0 0 0-2.658-.813A59.906 59.906 0 0 1 12 3.493a59.903 59.903 0 0 1 10.399 5.84c-.896.248-1.783.52-2.658.814m-15.482 0A50.717 50.717 0 0 1 12 13.489a50.702 50.702 0 0 1 7.74-3.342M6.75 15a.75.75 0 1 0 0-1.5.75.75 0 0 0 0 1.5Zm0 0v-3.675A55.378 55.378 0 0 1 12 8.443m-7.007 11.55A5.981 5.981 0 0 0 6.75 15.75v-1.5" />
                </svg>
                <p class="mt-3 text-sm text-gray-500">No training records found.</p>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>

    
    <div class="bg-white shadow-sm rounded-xl border border-gray-100">
        <div class="px-6 py-5 border-b border-gray-100">
            <div class="flex items-center gap-3">
                <span class="flex items-center justify-center w-9 h-9 rounded-lg bg-amber-50">
                    <svg class="w-5 h-5 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                    </svg>
                </span>
                <h3 class="text-base font-semibold text-gray-900">Actions History</h3>
            </div>
        </div>

        <div class="p-6">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($staff->actions->count() > 0): ?>
            <div class="overflow-x-auto -mx-6 px-6">
                <table class="min-w-full">
                    <thead>
                        <tr class="border-b border-gray-100">
                            <th class="pb-3 pr-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Action Type</th>
                            <th class="pb-3 px-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Period</th>
                            <th class="pb-3 px-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Status</th>
                            <th class="pb-3 px-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Due Date</th>
                            <th class="pb-3 px-4 text-left text-xs font-semibold uppercase tracking-wider text-gray-500">Created</th>
                            <th class="pb-3 pl-4 text-right text-xs font-semibold uppercase tracking-wider text-gray-500"></th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-gray-50">
                        <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $staff->actions; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 pr-4 whitespace-nowrap">
                                <span class="text-sm font-medium text-gray-900"><?php echo e($action->actionType->name); ?></span>
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                <span class="text-sm text-gray-600"><?php echo e($action->period); ?></span>
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 text-xs font-semibold rounded-full <?php echo e($action->status_badge_class); ?>">
                                    <?php echo e($action->status_label); ?>

                                </span>
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($action->due_date): ?>
                                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($action->is_overdue): ?>
                                        <span class="inline-flex items-center gap-1.5 text-sm font-medium text-red-600">
                                            <svg class="w-4 h-4 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                                            </svg>
                                            <?php echo e($action->due_date->format('d M Y')); ?>

                                            <span class="text-xs font-semibold px-1.5 py-0.5 rounded bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/10">Overdue</span>
                                        </span>
                                    <?php else: ?>
                                        <span class="text-sm text-gray-600"><?php echo e($action->due_date->format('d M Y')); ?></span>
                                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                                <?php else: ?>
                                    <span class="text-sm text-gray-400">-</span>
                                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                            </td>
                            <td class="py-4 px-4 whitespace-nowrap">
                                <span class="text-sm text-gray-500"><?php echo e($action->created_at->format('d M Y')); ?></span>
                            </td>
                            <td class="py-4 pl-4 whitespace-nowrap text-right">
                                <a href="<?php echo e(route('actions.show', $action)); ?>"
                                   class="inline-flex items-center gap-1 text-sm font-medium text-indigo-600 hover:text-indigo-800 transition-colors duration-150">
                                    View
                                    <svg class="w-3.5 h-3.5" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" d="m8.25 4.5 7.5 7.5-7.5 7.5" />
                                    </svg>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                    </tbody>
                </table>
            </div>
            <?php else: ?>
            <div class="text-center py-10">
                <svg class="mx-auto h-10 w-10 text-gray-300" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                </svg>
                <p class="mt-3 text-sm text-gray-500">No actions recorded.</p>
            </div>
            <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </div>
    </div>
 <?php echo $__env->renderComponent(); ?>
<?php endif; ?>
<?php if (isset($__attributesOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $attributes = $__attributesOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__attributesOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php if (isset($__componentOriginal5863877a5171c196453bfa0bd807e410)): ?>
<?php $component = $__componentOriginal5863877a5171c196453bfa0bd807e410; ?>
<?php unset($__componentOriginal5863877a5171c196453bfa0bd807e410); ?>
<?php endif; ?>
<?php /**PATH /Users/axmeen/Projects/staff-performance/resources/views/staff/history.blade.php ENDPATH**/ ?>