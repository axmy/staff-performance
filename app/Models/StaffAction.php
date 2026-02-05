<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StaffAction extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'action_trigger_id',
        'action_type_id',
        'year',
        'month',
        'trigger_data',
        'triggered_at',
        'status',
        'due_date',
        'assigned_to',
        'created_by',
        'notes',
        'completed_at',
    ];

    protected $casts = [
        'trigger_data' => 'array',
        'triggered_at' => 'datetime',
        'due_date' => 'date',
        'completed_at' => 'datetime',
    ];

    // Relationships
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function actionTrigger()
    {
        return $this->belongsTo(ActionTrigger::class);
    }

    public function actionType()
    {
        return $this->belongsTo(ActionType::class);
    }

    public function assignee()
    {
        return $this->belongsTo(User::class, 'assigned_to');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function documents()
    {
        return $this->hasMany(ActionDocument::class);
    }

    public function feedbacks()
    {
        return $this->hasMany(ActionFeedback::class);
    }

    // Scopes
    public function scopePending($query)
    {
        return $query->where('status', 'pending');
    }

    public function scopeInProgress($query)
    {
        return $query->where('status', 'in_progress');
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeOverdue($query)
    {
        return $query->where('due_date', '<', now()->toDateString())
            ->whereNotIn('status', ['completed', 'cancelled']);
    }

    public function scopeForPeriod($query, int $year, int $month)
    {
        return $query->where('year', $year)->where('month', $month);
    }

    public function scopeAssignedTo($query, int $userId)
    {
        return $query->where('assigned_to', $userId);
    }

    // Accessors
    public function getPeriodAttribute(): string
    {
        $monthName = date('F', mktime(0, 0, 0, $this->month, 1));
        return $monthName . ' ' . $this->year;
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'pending' => 'bg-yellow-100 text-yellow-800',
            'in_progress' => 'bg-blue-100 text-blue-800',
            'completed' => 'bg-green-100 text-green-800',
            'cancelled' => 'bg-gray-100 text-gray-800',
            'escalated' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->status) {
            'pending' => 'Pending',
            'in_progress' => 'In Progress',
            'completed' => 'Completed',
            'cancelled' => 'Cancelled',
            'escalated' => 'Escalated',
            default => $this->status,
        };
    }

    public function getIsOverdueAttribute(): bool
    {
        return $this->due_date &&
            $this->due_date->isPast() &&
            !in_array($this->status, ['completed', 'cancelled']);
    }

    public function getHasDocumentsAttribute(): bool
    {
        return $this->documents()->exists();
    }

    public function getHasFeedbackAttribute(): bool
    {
        return $this->feedbacks()->exists();
    }

    // Methods
    public function markAsInProgress(): void
    {
        $this->update(['status' => 'in_progress']);
    }

    public function markAsCompleted(?string $notes = null): void
    {
        $this->update([
            'status' => 'completed',
            'completed_at' => now(),
            'notes' => $notes ?? $this->notes,
        ]);
    }

    public function markAsCancelled(?string $reason = null): void
    {
        $this->update([
            'status' => 'cancelled',
            'notes' => $reason ?? $this->notes,
        ]);
    }

    public function escalate(?string $reason = null): void
    {
        $this->update([
            'status' => 'escalated',
            'notes' => $reason ?? $this->notes,
        ]);
    }

    public function assignTo(int $userId): void
    {
        $this->update(['assigned_to' => $userId]);
    }

    public static function createFromTrigger(Staff $staff, ActionTrigger $trigger, int $year, int $month, ?int $createdBy = null): self
    {
        return static::create([
            'staff_id' => $staff->id,
            'action_trigger_id' => $trigger->id,
            'action_type_id' => $trigger->action_type_id,
            'year' => $year,
            'month' => $month,
            'trigger_data' => [
                'trigger_name' => $trigger->name,
                'condition' => $trigger->condition_text,
                'value' => $trigger->getStaffValue($staff, $year, $month),
            ],
            'triggered_at' => now(),
            'status' => 'pending',
            'due_date' => now()->addDays(7),
            'created_by' => $createdBy,
        ]);
    }
}
