<?php

namespace Database\Seeders;

use App\Models\ActionTrigger;
use App\Models\ActionType;
use Illuminate\Database\Seeder;

class DefaultTriggerSeeder extends Seeder
{
    public function run(): void
    {
        // Get action types
        $warningLetter = ActionType::where('code', 'warning_letter')->first();
        $meeting = ActionType::where('code', 'meeting')->first();
        $trainingAssignment = ActionType::where('code', 'training_assignment')->first();
        $verbalWarning = ActionType::where('code', 'verbal_warning')->first();

        $triggers = [
            // Attendance triggers
            [
                'name' => 'High Absence Rate',
                'description' => 'Triggered when staff has 6 or more absent days in a month',
                'trigger_type' => 'attendance',
                'condition_field' => 'absent_days',
                'condition_operator' => '>=',
                'threshold_value' => 6,
                'action_type_id' => $warningLetter?->id,
                'period_type' => 'monthly',
                'priority' => 10,
            ],
            [
                'name' => 'Excessive Late Days',
                'description' => 'Triggered when staff has 10 or more late days in a month',
                'trigger_type' => 'attendance',
                'condition_field' => 'late_days',
                'condition_operator' => '>=',
                'threshold_value' => 10,
                'action_type_id' => $meeting?->id,
                'period_type' => 'monthly',
                'priority' => 8,
            ],
            [
                'name' => 'Moderate Absence',
                'description' => 'Triggered when staff has 3 or more absent days in a month',
                'trigger_type' => 'attendance',
                'condition_field' => 'absent_days',
                'condition_operator' => '>=',
                'threshold_value' => 3,
                'action_type_id' => $verbalWarning?->id,
                'period_type' => 'monthly',
                'priority' => 5,
            ],
            [
                'name' => 'Frequent Lateness',
                'description' => 'Triggered when staff has 5 or more late days in a month',
                'trigger_type' => 'attendance',
                'condition_field' => 'late_days',
                'condition_operator' => '>=',
                'threshold_value' => 5,
                'action_type_id' => $verbalWarning?->id,
                'period_type' => 'monthly',
                'priority' => 4,
            ],

            // Training triggers
            [
                'name' => 'Missed Training Sessions',
                'description' => 'Triggered when staff misses 3 or more training sessions in a month',
                'trigger_type' => 'training',
                'condition_field' => 'missed_trainings',
                'condition_operator' => '>=',
                'threshold_value' => 3,
                'action_type_id' => $trainingAssignment?->id,
                'period_type' => 'monthly',
                'priority' => 7,
            ],
            [
                'name' => 'Late to Trainings',
                'description' => 'Triggered when staff is late to 3 or more training sessions in a month',
                'trigger_type' => 'training',
                'condition_field' => 'late_to_trainings',
                'condition_operator' => '>=',
                'threshold_value' => 3,
                'action_type_id' => $meeting?->id,
                'period_type' => 'monthly',
                'priority' => 3,
            ],
        ];

        foreach ($triggers as $trigger) {
            if ($trigger['action_type_id']) {
                ActionTrigger::firstOrCreate(
                    ['name' => $trigger['name']],
                    [...$trigger, 'is_active' => true, 'auto_trigger' => true],
                );
            }
        }
    }
}
