<?php

namespace App\Services;

use App\Models\Staff;
use App\Models\ActionTrigger;
use App\Models\StaffAction;
use Illuminate\Support\Facades\Log;

class ActionTriggerService
{
    public function processTriggersForPeriod(int $year, int $month): array
    {
        $triggers = ActionTrigger::active()->autoTrigger()->byPriority()->get();
        $staff = Staff::active()->get();

        $actionsCreated = 0;
        $errors = [];

        foreach ($staff as $staffMember) {
            foreach ($triggers as $trigger) {
                try {
                    if ($this->shouldTriggerAction($staffMember, $trigger, $year, $month)) {
                        $this->createAction($staffMember, $trigger, $year, $month);
                        $actionsCreated++;
                    }
                } catch (\Exception $e) {
                    $errors[] = "Error processing trigger '{$trigger->name}' for staff '{$staffMember->name}': " . $e->getMessage();
                    Log::error('Action trigger processing error', [
                        'staff_id' => $staffMember->id,
                        'trigger_id' => $trigger->id,
                        'error' => $e->getMessage(),
                    ]);
                }
            }
        }

        return [
            'staff_count' => $staff->count(),
            'triggers_count' => $triggers->count(),
            'actions_created' => $actionsCreated,
            'errors' => $errors,
        ];
    }

    public function processTriggersForStaff(Staff $staff, int $year, int $month): array
    {
        $triggers = ActionTrigger::active()->autoTrigger()->byPriority()->get();
        $actionsCreated = [];

        foreach ($triggers as $trigger) {
            if ($this->shouldTriggerAction($staff, $trigger, $year, $month)) {
                $action = $this->createAction($staff, $trigger, $year, $month);
                $actionsCreated[] = $action;
            }
        }

        return $actionsCreated;
    }

    protected function shouldTriggerAction(Staff $staff, ActionTrigger $trigger, int $year, int $month): bool
    {
        // Check if trigger condition is met
        if (!$trigger->evaluateForStaff($staff, $year, $month)) {
            return false;
        }

        // Check if action already exists for this staff, trigger, and period
        $existingAction = StaffAction::where('staff_id', $staff->id)
            ->where('action_trigger_id', $trigger->id)
            ->where('year', $year)
            ->where('month', $month)
            ->exists();

        return !$existingAction;
    }

    protected function createAction(Staff $staff, ActionTrigger $trigger, int $year, int $month): StaffAction
    {
        return StaffAction::createFromTrigger($staff, $trigger, $year, $month, auth()->id());
    }

    public function evaluateStaffForTriggers(Staff $staff, int $year, int $month): array
    {
        $triggers = ActionTrigger::active()->get();
        $triggeredConditions = [];

        foreach ($triggers as $trigger) {
            $value = $trigger->getStaffValue($staff, $year, $month);

            if ($trigger->checkCondition($value)) {
                $triggeredConditions[] = [
                    'trigger' => $trigger,
                    'value' => $value,
                    'condition_met' => true,
                    'action_type' => $trigger->actionType,
                    'has_existing_action' => StaffAction::where('staff_id', $staff->id)
                        ->where('action_trigger_id', $trigger->id)
                        ->where('year', $year)
                        ->where('month', $month)
                        ->exists(),
                ];
            }
        }

        return $triggeredConditions;
    }

    public function getStaffViolations(int $year, int $month): array
    {
        $triggers = ActionTrigger::active()->get();
        $staff = Staff::active()->get();
        $violations = [];

        foreach ($staff as $staffMember) {
            $staffViolations = [];

            foreach ($triggers as $trigger) {
                $value = $trigger->getStaffValue($staffMember, $year, $month);

                if ($trigger->checkCondition($value)) {
                    $staffViolations[] = [
                        'trigger_name' => $trigger->name,
                        'condition' => $trigger->condition_text,
                        'actual_value' => $value,
                        'threshold' => $trigger->threshold_value,
                        'action_type' => $trigger->actionType->name,
                    ];
                }
            }

            if (!empty($staffViolations)) {
                $violations[] = [
                    'staff' => $staffMember,
                    'violations' => $staffViolations,
                ];
            }
        }

        return $violations;
    }
}
