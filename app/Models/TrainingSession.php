<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class TrainingSession extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'title',
        'description',
        'trainer',
        'location',
        'scheduled_date',
        'scheduled_time',
        'duration_minutes',
        'status',
        'created_by',
    ];

    protected $casts = [
        'scheduled_date' => 'date',
        'scheduled_time' => 'datetime:H:i',
    ];

    // Relationships
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function notifications()
    {
        return $this->hasMany(TrainingNotification::class);
    }

    public function attendances()
    {
        return $this->hasMany(TrainingAttendance::class);
    }

    public function assignedStaff()
    {
        return $this->belongsToMany(Staff::class, 'training_notifications')
            ->withPivot(['notified_at', 'notification_method', 'response', 'response_at'])
            ->withTimestamps();
    }

    // Scopes
    public function scopeUpcoming($query)
    {
        return $query->where('status', 'upcoming')
            ->where('scheduled_date', '>=', now()->toDateString());
    }

    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeInMonth($query, int $year, int $month)
    {
        return $query->whereYear('scheduled_date', $year)
            ->whereMonth('scheduled_date', $month);
    }

    // Accessors
    public function getFormattedDateAttribute(): string
    {
        return $this->scheduled_date->format('d M Y');
    }

    public function getFormattedTimeAttribute(): ?string
    {
        return $this->scheduled_time?->format('H:i');
    }

    public function getDurationTextAttribute(): ?string
    {
        if (!$this->duration_minutes) return null;

        $hours = intdiv($this->duration_minutes, 60);
        $minutes = $this->duration_minutes % 60;

        if ($hours > 0 && $minutes > 0) {
            return "{$hours}h {$minutes}m";
        } elseif ($hours > 0) {
            return "{$hours}h";
        }
        return "{$minutes}m";
    }

    public function getAssignedCountAttribute(): int
    {
        return $this->notifications()->count();
    }

    public function getPresentCountAttribute(): int
    {
        return $this->attendances()->where('attendance_status', 'present')->count();
    }

    public function getAbsentCountAttribute(): int
    {
        return $this->attendances()->whereIn('attendance_status', ['absent', 'on_leave'])->count();
    }

    public function getAttendanceRateAttribute(): float
    {
        $total = $this->attendances()->count();
        if ($total === 0) return 0;

        $present = $this->attendances()->whereIn('attendance_status', ['present', 'late'])->count();
        return round(($present / $total) * 100, 1);
    }

    // Status helpers
    public function isUpcoming(): bool
    {
        return $this->status === 'upcoming';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isCancelled(): bool
    {
        return $this->status === 'cancelled';
    }

    public function canMarkAttendance(): bool
    {
        return in_array($this->status, ['ongoing', 'completed']);
    }
}
