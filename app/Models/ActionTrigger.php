<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionTrigger extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'description',
        'trigger_type',
        'condition_field',
        'condition_operator',
        'threshold_value',
        'action_type_id',
        'period_type',
        'is_active',
        'auto_trigger',
        'priority',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'auto_trigger' => 'boolean',
    ];

    // Relationships
    public function actionType()
    {
        return $this->belongsTo(ActionType::class);
    }

    public function staffActions()
    {
        return $this->hasMany(StaffAction::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function scopeAutoTrigger($query)
    {
        return $query->where('auto_trigger', true);
    }

    public function scopeForAttendance($query)
    {
        return $query->where('trigger_type', 'attendance');
    }

    public function scopeForTraining($query)
    {
        return $query->where('trigger_type', 'training');
    }

    public function scopeByPriority($query)
    {
        return $query->orderByDesc('priority');
    }

    // Accessors
    public function getConditionTextAttribute(): string
    {
        $fieldLabels = [
            'absent_days' => 'Absent Days',
            'late_days' => 'Late Days',
            'leave_days' => 'Leave Days',
            'missed_trainings' => 'Missed Trainings',
            'late_to_trainings' => 'Late to Trainings',
        ];

        $operatorLabels = [
            '>=' => 'is at least',
            '>' => 'is more than',
            '=' => 'equals',
            '<=' => 'is at most',
            '<' => 'is less than',
        ];

        $field = $fieldLabels[$this->condition_field] ?? $this->condition_field;
        $operator = $operatorLabels[$this->condition_operator] ?? $this->condition_operator;

        return "{$field} {$operator} {$this->threshold_value}";
    }

    public function getPeriodLabelAttribute(): string
    {
        return match($this->period_type) {
            'monthly' => 'Monthly',
            'quarterly' => 'Quarterly',
            'yearly' => 'Yearly',
            'cumulative' => 'Cumulative',
            default => $this->period_type,
        };
    }

    public function getTriggerTypeLabelAttribute(): string
    {
        return match($this->trigger_type) {
            'attendance' => 'Attendance',
            'training' => 'Training',
            default => $this->trigger_type,
        };
    }

    // Methods
    public function checkCondition(int $value): bool
    {
        return match($this->condition_operator) {
            '>=' => $value >= $this->threshold_value,
            '>' => $value > $this->threshold_value,
            '=' => $value == $this->threshold_value,
            '<=' => $value <= $this->threshold_value,
            '<' => $value < $this->threshold_value,
            default => false,
        };
    }

    public function evaluateForStaff(Staff $staff, int $year, int $month): bool
    {
        $value = $this->getStaffValue($staff, $year, $month);
        return $this->checkCondition($value);
    }

    public function getStaffValue(Staff $staff, int $year, int $month): int
    {
        if ($this->trigger_type === 'attendance') {
            $attendance = $staff->getAttendanceForMonth($year, $month);
            if (!$attendance) return 0;

            return match($this->condition_field) {
                'absent_days' => $attendance->days_absent,
                'late_days' => $attendance->days_late,
                'leave_days' => $attendance->days_leave,
                default => 0,
            };
        }

        if ($this->trigger_type === 'training') {
            return match($this->condition_field) {
                'missed_trainings' => $staff->getMissedTrainingsCount($year, $month),
                'late_to_trainings' => $staff->getLateTrainingsCount($year, $month),
                default => 0,
            };
        }

        return 0;
    }
}
