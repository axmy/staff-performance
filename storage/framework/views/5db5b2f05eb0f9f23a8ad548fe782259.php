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
     <?php $__env->slot('title', null, []); ?> Import Details <?php $__env->endSlot(); ?>

    <!-- Header -->
    <div class="mb-8 flex justify-between items-center">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Import Details</h1>
            <p class="mt-1 text-sm text-gray-500"><?php echo e($import->original_file_name ?? $import->file_name); ?> &mdash; <?php echo e(date('F Y', mktime(0, 0, 0, $import->month, 1, $import->year))); ?></p>
        </div>
        <div class="flex items-center gap-3">
            <form method="POST" action="<?php echo e(route('attendance.destroy', $import)); ?>"
                  onsubmit="return confirm('Delete this entire import and all its attendance records? This cannot be undone.')">
                <?php echo csrf_field(); ?>
                <?php echo method_field('DELETE'); ?>
                <button type="submit"
                        class="px-6 py-3 rounded-lg font-semibold text-white bg-red-600 hover:bg-red-700 active:bg-red-800 shadow-sm hover:shadow-md transition-all duration-200 focus:ring-2 focus:ring-offset-2 focus:ring-red-500 text-sm">
                    Delete Import
                </button>
            </form>
            <a href="<?php echo e(route('attendance.history')); ?>"
               class="px-6 py-3 rounded-lg font-semibold border border-gray-300 bg-white hover:bg-gray-50 transition-all duration-200 text-gray-700 text-sm">
                Back to History
            </a>
        </div>
    </div>

    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if(session('success')): ?>
    <div class="mb-6 rounded-lg bg-green-50 border border-green-200 p-4">
        <p class="text-sm text-green-800"><?php echo e(session('success')); ?></p>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Summary Cards -->
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-4 mb-8">
        <div class="bg-white shadow-sm rounded-xl border border-gray-100 p-5">
            <p class="text-sm font-medium text-gray-500">Status</p>
            <p class="mt-1">
                <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-semibold <?php echo e($import->status_badge_class); ?>">
                    <?php echo e(ucfirst($import->status)); ?>

                </span>
            </p>
        </div>
        <div class="bg-white shadow-sm rounded-xl border border-gray-100 p-5">
            <p class="text-sm font-medium text-gray-500">Imported By</p>
            <p class="mt-1 text-lg font-semibold text-gray-900"><?php echo e($import->importer->name ?? 'N/A'); ?></p>
        </div>
        <div class="bg-white shadow-sm rounded-xl border border-gray-100 p-5">
            <p class="text-sm font-medium text-gray-500">Success / Errors</p>
            <p class="mt-1 text-lg font-semibold text-gray-900">
                <span class="text-green-600"><?php echo e($import->success_count ?? 0); ?></span>
                /
                <span class="text-red-600"><?php echo e($import->error_count ?? 0); ?></span>
            </p>
        </div>
        <div class="bg-white shadow-sm rounded-xl border border-gray-100 p-5">
            <p class="text-sm font-medium text-gray-500">Imported On</p>
            <p class="mt-1 text-lg font-semibold text-gray-900"><?php echo e($import->created_at->format('d M Y, H:i')); ?></p>
        </div>
    </div>

    <!-- Error Log -->
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($import->has_errors && !empty($import->error_log)): ?>
    <div class="bg-red-50 border border-red-200 rounded-xl p-5 mb-8">
        <h3 class="text-sm font-semibold text-red-800 mb-2">Import Errors</h3>
        <ul class="list-disc list-inside space-y-1">
            <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $import->error_log; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $error): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
            <li class="text-sm text-red-700"><?php echo e($error); ?></li>
            <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
        </ul>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>

    <!-- Attendance Records -->
    <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden" x-data="{ editingId: null }">
        <div class="px-6 py-4 border-b border-gray-200">
            <h3 class="text-lg font-medium text-gray-900">Imported Records</h3>
        </div>
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Staff</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Working Days</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Present</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Absent</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Leave</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Late</th>
                    <th class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Attendance %</th>
                    <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__empty_1 = true; $__currentLoopData = $import->monthlyAttendances; $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $attendance): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); $__empty_1 = false; ?>
                <!-- Display Row -->
                <tr class="hover:bg-gray-50 transition-colors" x-show="editingId !== <?php echo e($attendance->id); ?>">
                    <td class="px-6 py-4 whitespace-nowrap">
                        <div class="text-sm font-medium text-gray-900"><?php echo e($attendance->staff->name ?? 'Unknown'); ?></div>
                        <div class="text-xs text-gray-500"><?php echo e($attendance->staff->record_card_number ?? ''); ?></div>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-gray-900">
                        <?php echo e($attendance->total_working_days); ?>

                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-green-600 font-medium">
                        <?php echo e($attendance->days_present); ?>

                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-red-600 font-medium">
                        <?php echo e($attendance->days_absent); ?>

                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-yellow-600 font-medium">
                        <?php echo e($attendance->days_leave); ?>

                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm text-orange-600 font-medium">
                        <?php echo e($attendance->days_late); ?>

                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-center">
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?php echo e($attendance->status_badge_class); ?>">
                            <?php echo e($attendance->attendance_percentage); ?>%
                        </span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                        <button @click="editingId = <?php echo e($attendance->id); ?>"
                                class="font-medium text-indigo-600 hover:text-indigo-800 transition-colors mr-3">
                            Edit
                        </button>
                        <form method="POST" action="<?php echo e(route('attendance.records.destroy', $attendance)); ?>" class="inline"
                              onsubmit="return confirm('Delete this attendance record?')">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('DELETE'); ?>
                            <button type="submit" class="font-medium text-red-600 hover:text-red-800 transition-colors">
                                Delete
                            </button>
                        </form>
                    </td>
                </tr>
                <!-- Edit Row -->
                <tr x-show="editingId === <?php echo e($attendance->id); ?>" x-cloak class="bg-gray-50/50">
                    <td colspan="8" class="px-6 py-4">
                        <form method="POST" action="<?php echo e(route('attendance.records.update', $attendance)); ?>" class="flex items-end gap-4 flex-wrap">
                            <?php echo csrf_field(); ?>
                            <?php echo method_field('PUT'); ?>
                            <div class="text-sm font-medium text-gray-900 min-w-[140px]">
                                <?php echo e($attendance->staff->name ?? 'Unknown'); ?>

                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Working Days</label>
                                <input type="number" name="total_working_days" value="<?php echo e($attendance->total_working_days); ?>" min="1"
                                       class="w-20 rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Present</label>
                                <input type="number" name="days_present" value="<?php echo e($attendance->days_present); ?>" min="0"
                                       class="w-20 rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Absent</label>
                                <input type="number" name="days_absent" value="<?php echo e($attendance->days_absent); ?>" min="0"
                                       class="w-20 rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Leave</label>
                                <input type="number" name="days_leave" value="<?php echo e($attendance->days_leave); ?>" min="0"
                                       class="w-20 rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-500 mb-1">Late</label>
                                <input type="number" name="days_late" value="<?php echo e($attendance->days_late); ?>" min="0"
                                       class="w-20 rounded-lg border-gray-300 text-sm shadow-sm focus:border-indigo-500 focus:ring-indigo-500 transition-colors">
                            </div>
                            <div class="flex items-center gap-2">
                                <button type="submit"
                                        class="px-4 py-2 rounded-lg font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 shadow-sm text-sm transition-all duration-200">
                                    Save
                                </button>
                                <button type="button" @click="editingId = null"
                                        class="px-4 py-2 rounded-lg font-semibold border border-gray-300 bg-white hover:bg-gray-50 text-gray-700 text-sm transition-all duration-200">
                                    Cancel
                                </button>
                            </div>
                        </form>
                    </td>
                </tr>
                <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); if ($__empty_1): ?>
                <tr>
                    <td colspan="8" class="px-6 py-8 text-center text-gray-500">
                        No attendance records found for this import.
                    </td>
                </tr>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </tbody>
        </table>
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
<?php /**PATH /Users/axmeen/Projects/staff-performance/resources/views/attendance/show.blade.php ENDPATH**/ ?>