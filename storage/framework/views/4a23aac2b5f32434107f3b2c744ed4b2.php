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
     <?php $__env->slot('title', null, []); ?> Mark Attendance - <?php echo e($training->title); ?> <?php $__env->endSlot(); ?>

    <div class="mb-6">
        <a href="<?php echo e(route('training.show', $training)); ?>" class="text-indigo-600 hover:text-indigo-900">&larr; Back to Training Details</a>
    </div>

    <div class="bg-white shadow-sm rounded-xl border border-gray-100 mb-6">
        <div class="px-4 py-5 sm:p-6">
            <h2 class="text-lg font-medium text-gray-900 mb-2"><?php echo e($training->title); ?></h2>
            <p class="text-sm text-gray-500">
                <?php echo e($training->scheduled_date?->format('d M Y')); ?> at <?php echo e($training->formatted_time ?? '-'); ?> | <?php echo e($training->location ?? 'Location TBD'); ?>

            </p>
        </div>
    </div>

    <form method="POST" action="<?php echo e(route('training.attendance.save', $training)); ?>">
        <?php echo csrf_field(); ?>

        <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Staff Member</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Record Card</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Attendance Status</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Reason</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $assignedStaff; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $key => $entry): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                    <?php
                        $staff = $entry['staff'];
                        $attendance = $entry['attendance'];
                    ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <span class="inline-flex items-center justify-center h-10 w-10 rounded-full bg-indigo-500">
                                        <span class="text-sm font-medium leading-none text-white">
                                            <?php echo e(substr($staff->name, 0, 2)); ?>

                                        </span>
                                    </span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900"><?php echo e($staff->name); ?></div>
                                </div>
                            </div>
                            <input type="hidden" name="attendance[<?php echo e($key); ?>][staff_id]" value="<?php echo e($staff->id); ?>">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo e($staff->record_card_number); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo e($staff->department?->name ?? '-'); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <select name="attendance[<?php echo e($key); ?>][status]"
                                    class="block w-full rounded-lg border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                                <option value="present" <?php if($attendance && $attendance->attendance_status === 'present'): ?> selected <?php endif; ?>>Present</option>
                                <option value="late" <?php if($attendance && $attendance->attendance_status === 'late'): ?> selected <?php endif; ?>>Late</option>
                                <option value="absent" <?php if($attendance && $attendance->attendance_status === 'absent'): ?> selected <?php endif; ?>>Absent</option>
                                <option value="on_leave" <?php if($attendance && $attendance->attendance_status === 'on_leave'): ?> selected <?php endif; ?>>On Leave</option>
                                <option value="excused" <?php if($attendance && $attendance->attendance_status === 'excused'): ?> selected <?php endif; ?>>Excused</option>
                            </select>
                        </td>
                        <td class="px-6 py-4">
                            <textarea name="attendance[<?php echo e($key); ?>][reason]" rows="2"
                                      placeholder="Reason for absence, late, or leave..."
                                      class="block w-full rounded-lg border-gray-300 bg-white px-3 py-2 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors"><?php echo e($attendance?->absence_reason ?? ''); ?></textarea>
                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-4 text-center text-gray-500">
                            No staff members assigned to this training.
                        </td>
                    </tr>
                    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>

        <div class="mt-6 flex justify-end space-x-3">
            <a href="<?php echo e(route('training.show', $training)); ?>"
               class="inline-flex items-center px-6 py-3 rounded-lg font-semibold border border-gray-300 bg-white hover:bg-gray-50 transition-all duration-200 text-gray-700 text-sm">
                Cancel
            </a>
            <button type="submit"
                    class="inline-flex items-center px-6 py-3 rounded-lg font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 shadow-sm hover:shadow-md transition-all duration-200 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-sm">
                Save Attendance
            </button>
        </div>
    </form>
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
<?php /**PATH /Users/axmeen/Projects/staff-performance/resources/views/training/attendance.blade.php ENDPATH**/ ?>