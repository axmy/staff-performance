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
     <?php $__env->slot('title', null, []); ?> <?php echo e($staff->name); ?> <?php $__env->endSlot(); ?>

    
    <div class="mb-8 flex flex-col sm:flex-row sm:justify-between sm:items-center gap-4">
        <a href="<?php echo e(route('staff.index')); ?>" class="text-indigo-600 hover:text-indigo-900 inline-flex items-center gap-1.5 text-sm font-medium transition-colors duration-150">
            &larr; Back to Staff List
        </a>
        <div class="flex items-center gap-3">
            <a href="<?php echo e(route('staff.history', $staff)); ?>"
               class="inline-flex items-center px-6 py-3 rounded-lg font-semibold border border-gray-300 bg-white hover:bg-gray-50 transition-all duration-200 text-gray-700 text-sm">
                <svg class="w-4 h-4 mr-2 text-gray-400" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M12 6v6h4.5m4.5 0a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                </svg>
                View History
            </a>
            <a href="<?php echo e(route('staff.edit', $staff)); ?>"
               class="inline-flex items-center px-6 py-3 rounded-lg font-semibold text-white bg-indigo-600 hover:bg-indigo-700 active:bg-indigo-800 shadow-sm hover:shadow-md transition-all duration-200 focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 text-sm">
                <svg class="w-4 h-4 mr-2" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" d="m16.862 4.487 1.687-1.688a1.875 1.875 0 1 1 2.652 2.652L10.582 16.07a4.5 4.5 0 0 1-1.897 1.13L6 18l.8-2.685a4.5 4.5 0 0 1 1.13-1.897l8.932-8.931Zm0 0L19.5 7.125" />
                </svg>
                Edit Staff
            </a>
        </div>
    </div>

    
    <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
        
        <div class="px-6 py-6 sm:px-8">
            <div class="flex items-center">
                <div class="flex-shrink-0">
                    <span class="inline-flex items-center justify-center h-16 w-16 rounded-full bg-indigo-500 shadow-md ring-4 ring-indigo-50">
                        <span class="text-xl font-semibold leading-none text-white tracking-wide">
                            <?php echo e(substr($staff->name, 0, 2)); ?>

                        </span>
                    </span>
                </div>
                <div class="ml-5">
                    <h3 class="text-xl font-bold text-gray-900"><?php echo e($staff->name); ?></h3>
                    <p class="mt-0.5 text-sm text-gray-500 font-mono"><?php echo e($staff->record_card_number); ?></p>
                </div>
                <div class="ml-auto">
                    <span class="px-3.5 py-1.5 inline-flex text-xs leading-5 font-semibold rounded-full tracking-wide uppercase
                        <?php if($staff->status === 'active'): ?> bg-green-50 text-green-700 ring-1 ring-inset ring-green-600/20
                        <?php elseif($staff->status === 'inactive'): ?> bg-yellow-50 text-yellow-700 ring-1 ring-inset ring-yellow-600/20
                        <?php else: ?> bg-red-50 text-red-700 ring-1 ring-inset ring-red-600/20 <?php endif; ?>">
                        <?php echo e(ucfirst($staff->status)); ?>

                    </span>
                </div>
            </div>
        </div>

        
        <div class="border-t border-gray-100">
            <dl class="divide-y divide-gray-100">
                <div class="px-6 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-8">
                    <dt class="text-sm font-medium text-gray-500">Department</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?php echo e($staff->department?->name ?? '-'); ?></dd>
                </div>
                <div class="px-6 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-8">
                    <dt class="text-sm font-medium text-gray-500">Designation</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?php echo e($staff->designation?->name ?? '-'); ?></dd>
                </div>
                <div class="px-6 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-8">
                    <dt class="text-sm font-medium text-gray-500">Email</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?php echo e($staff->email ?? '-'); ?></dd>
                </div>
                <div class="px-6 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-8">
                    <dt class="text-sm font-medium text-gray-500">Phone</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?php echo e($staff->phone ?? '-'); ?></dd>
                </div>
                <div class="px-6 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-8">
                    <dt class="text-sm font-medium text-gray-500">Joined Date</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                        <?php echo e($staff->joined_date?->format('d M Y') ?? '-'); ?>

                    </dd>
                </div>
                <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($staff->notes): ?>
                <div class="px-6 py-4 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-8">
                    <dt class="text-sm font-medium text-gray-500">Notes</dt>
                    <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2 leading-relaxed"><?php echo e($staff->notes); ?></dd>
                </div>
                <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
            </dl>
        </div>
    </div>

    
    <div class="mt-8 grid grid-cols-1 gap-5 sm:grid-cols-3">
        
        <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-green-50 rounded-xl p-3">
                        <svg class="h-6 w-6 text-green-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M9 12.75 11.25 15 15 9.75M21 12a9 9 0 1 1-18 0 9 9 0 0 1 18 0Z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Attendance Records</dt>
                            <dd class="mt-1 text-2xl font-bold text-gray-900"><?php echo e($staff->monthlyAttendances->count()); ?></dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-blue-50 rounded-xl p-3">
                        <svg class="h-6 w-6 text-blue-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 6.042A8.967 8.967 0 0 0 6 3.75c-1.052 0-2.062.18-3 .512v14.25A8.987 8.987 0 0 1 6 18c2.305 0 4.408.867 6 2.292m0-14.25a8.966 8.966 0 0 1 6-2.292c1.052 0 2.062.18 3 .512v14.25A8.987 8.987 0 0 0 18 18a8.967 8.967 0 0 0-6 2.292m0-14.25v14.25" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Training Sessions</dt>
                            <dd class="mt-1 text-2xl font-bold text-gray-900"><?php echo e($staff->trainingAttendances->count()); ?></dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>

        
        <div class="bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
            <div class="p-6">
                <div class="flex items-center">
                    <div class="flex-shrink-0 bg-amber-50 rounded-xl p-3">
                        <svg class="h-6 w-6 text-amber-600" fill="none" viewBox="0 0 24 24" stroke-width="1.5" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M12 9v3.75m-9.303 3.376c-.866 1.5.217 3.374 1.948 3.374h14.71c1.73 0 2.813-1.874 1.948-3.374L13.949 3.378c-.866-1.5-3.032-1.5-3.898 0L2.697 16.126ZM12 15.75h.007v.008H12v-.008Z" />
                        </svg>
                    </div>
                    <div class="ml-5 w-0 flex-1">
                        <dl>
                            <dt class="text-sm font-medium text-gray-500 truncate">Pending Actions</dt>
                            <dd class="mt-1 text-2xl font-bold text-gray-900"><?php echo e($staff->getPendingActionsCount()); ?></dd>
                        </dl>
                    </div>
                </div>
            </div>
        </div>
    </div>

    
    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php if($staff->actions->count() > 0): ?>
    <div class="mt-8 bg-white shadow-sm rounded-xl border border-gray-100 overflow-hidden">
        <div class="px-6 py-5 sm:px-8">
            <h3 class="text-base font-semibold text-gray-900">Recent Actions</h3>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-100">
                <thead>
                    <tr class="bg-gray-50/80">
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Type</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Period</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="px-6 py-3.5 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Created</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    <?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if BLOCK]><![endif]--><?php endif; ?><?php $__currentLoopData = $staff->actions->take(5); $__env->addLoop($__currentLoopData); foreach($__currentLoopData as $action): $__env->incrementLoopIndices(); $loop = $__env->getLastLoop(); ?>
                    <tr class="hover:bg-gray-50 transition-colors">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            <?php echo e($action->actionType->name); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo e($action->period); ?>

                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2.5 py-0.5 inline-flex text-xs leading-5 font-semibold rounded-full <?php echo e($action->status_badge_class); ?>">
                                <?php echo e($action->status_label); ?>

                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?php echo e($action->created_at->format('d M Y')); ?>

                        </td>
                    </tr>
                    <?php endforeach; $__env->popLoop(); $loop = $__env->getLastLoop(); ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
    <?php endif; ?><?php if(\Livewire\Mechanisms\ExtendBlade\ExtendBlade::isRenderingLivewireComponent()): ?><!--[if ENDBLOCK]><![endif]--><?php endif; ?>
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
<?php /**PATH /Users/axmeen/Projects/staff-performance/resources/views/staff/show.blade.php ENDPATH**/ ?>