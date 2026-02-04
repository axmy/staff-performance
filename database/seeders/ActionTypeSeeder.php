<?php

namespace Database\Seeders;

use App\Models\ActionType;
use Illuminate\Database\Seeder;

class ActionTypeSeeder extends Seeder
{
    public function run(): void
    {
        $actionTypes = [
            [
                'name' => 'Warning Letter',
                'code' => 'warning_letter',
                'description' => 'Formal written warning issued to staff for attendance or performance issues',
                'icon' => 'exclamation-triangle',
                'color' => 'red',
                'requires_document' => true,
                'requires_feedback' => true,
                'sort_order' => 1,
            ],
            [
                'name' => 'Meeting/Counseling',
                'code' => 'meeting',
                'description' => 'One-on-one meeting or counseling session with staff to discuss performance',
                'icon' => 'users',
                'color' => 'blue',
                'requires_document' => false,
                'requires_feedback' => true,
                'sort_order' => 2,
            ],
            [
                'name' => 'Training Assignment',
                'code' => 'training_assignment',
                'description' => 'Assignment of additional training sessions for skill improvement',
                'icon' => 'academic-cap',
                'color' => 'green',
                'requires_document' => false,
                'requires_feedback' => false,
                'sort_order' => 3,
            ],
            [
                'name' => 'Performance Review',
                'code' => 'performance_review',
                'description' => 'Formal performance review and evaluation',
                'icon' => 'clipboard-check',
                'color' => 'purple',
                'requires_document' => true,
                'requires_feedback' => true,
                'sort_order' => 4,
            ],
            [
                'name' => 'Verbal Warning',
                'code' => 'verbal_warning',
                'description' => 'Informal verbal warning for minor issues',
                'icon' => 'chat-alt',
                'color' => 'yellow',
                'requires_document' => false,
                'requires_feedback' => false,
                'sort_order' => 5,
            ],
            [
                'name' => 'Suspension',
                'code' => 'suspension',
                'description' => 'Temporary suspension from work duties',
                'icon' => 'ban',
                'color' => 'red',
                'requires_document' => true,
                'requires_feedback' => true,
                'sort_order' => 6,
            ],
        ];

        foreach ($actionTypes as $actionType) {
            ActionType::firstOrCreate(
                ['code' => $actionType['code']],
                [...$actionType, 'is_active' => true],
            );
        }
    }
}
