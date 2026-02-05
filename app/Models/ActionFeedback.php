<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionFeedback extends Model
{
    use HasFactory;

    protected $table = 'action_feedbacks';

    protected $fillable = [
        'staff_action_id',
        'feedback_by',
        'feedback_user_id',
        'feedback_text',
        'feedback_date',
        'acknowledgment_status',
        'acknowledgment_notes',
        'acknowledged_at',
    ];

    protected $casts = [
        'feedback_date' => 'date',
        'acknowledged_at' => 'datetime',
    ];

    // Relationships
    public function staffAction()
    {
        return $this->belongsTo(StaffAction::class);
    }

    public function feedbackUser()
    {
        return $this->belongsTo(User::class, 'feedback_user_id');
    }

    // Scopes
    public function scopeFromStaff($query)
    {
        return $query->where('feedback_by', 'staff');
    }

    public function scopeFromManager($query)
    {
        return $query->where('feedback_by', 'manager');
    }

    public function scopePendingAcknowledgment($query)
    {
        return $query->where('acknowledgment_status', 'pending');
    }

    public function scopeAcknowledged($query)
    {
        return $query->where('acknowledgment_status', 'acknowledged');
    }

    // Accessors
    public function getFeedbackByLabelAttribute(): string
    {
        return match($this->feedback_by) {
            'staff' => 'Staff',
            'manager' => 'Manager',
            'hr' => 'HR',
            default => 'Other',
        };
    }

    public function getAcknowledgmentStatusBadgeClassAttribute(): string
    {
        return match($this->acknowledgment_status) {
            'acknowledged' => 'bg-green-100 text-green-800',
            'disputed' => 'bg-red-100 text-red-800',
            'appealed' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getAcknowledgmentStatusLabelAttribute(): string
    {
        return match($this->acknowledgment_status) {
            'pending' => 'Pending',
            'acknowledged' => 'Acknowledged',
            'disputed' => 'Disputed',
            'appealed' => 'Appealed',
            default => $this->acknowledgment_status,
        };
    }

    // Methods
    public function acknowledge(?string $notes = null): void
    {
        $this->update([
            'acknowledgment_status' => 'acknowledged',
            'acknowledgment_notes' => $notes,
            'acknowledged_at' => now(),
        ]);
    }

    public function dispute(?string $reason = null): void
    {
        $this->update([
            'acknowledgment_status' => 'disputed',
            'acknowledgment_notes' => $reason,
        ]);
    }

    public function appeal(?string $reason = null): void
    {
        $this->update([
            'acknowledgment_status' => 'appealed',
            'acknowledgment_notes' => $reason,
        ]);
    }
}
