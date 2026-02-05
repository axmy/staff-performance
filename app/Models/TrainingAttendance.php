<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'training_session_id',
        'staff_id',
        'attendance_status',
        'absence_reason',
        'marked_by',
        'marked_at',
    ];

    protected $casts = [
        'marked_at' => 'datetime',
    ];

    // Relationships
    public function trainingSession()
    {
        return $this->belongsTo(TrainingSession::class);
    }

    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function marker()
    {
        return $this->belongsTo(User::class, 'marked_by');
    }

    // Scopes
    public function scopePresent($query)
    {
        return $query->where('attendance_status', 'present');
    }

    public function scopeLate($query)
    {
        return $query->where('attendance_status', 'late');
    }

    public function scopeAbsent($query)
    {
        return $query->where('attendance_status', 'absent');
    }

    public function scopeOnLeave($query)
    {
        return $query->where('attendance_status', 'on_leave');
    }

    public function scopeAttended($query)
    {
        return $query->whereIn('attendance_status', ['present', 'late']);
    }

    public function scopeNotAttended($query)
    {
        return $query->whereIn('attendance_status', ['absent', 'on_leave']);
    }

    // Accessors
    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->attendance_status) {
            'present' => 'bg-green-100 text-green-800',
            'late' => 'bg-yellow-100 text-yellow-800',
            'absent' => 'bg-red-100 text-red-800',
            'on_leave' => 'bg-blue-100 text-blue-800',
            'excused' => 'bg-purple-100 text-purple-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getStatusLabelAttribute(): string
    {
        return match($this->attendance_status) {
            'present' => 'Present',
            'late' => 'Late',
            'absent' => 'Absent',
            'on_leave' => 'On Leave',
            'excused' => 'Excused',
            default => 'Unknown',
        };
    }

    // Methods
    public function markAttendance(string $status, ?string $reason = null, ?int $markedBy = null): void
    {
        $this->update([
            'attendance_status' => $status,
            'absence_reason' => in_array($status, ['absent', 'on_leave', 'excused']) ? $reason : null,
            'marked_by' => $markedBy,
            'marked_at' => now(),
        ]);
    }
}
