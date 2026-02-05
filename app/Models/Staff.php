<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Staff extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'staff';

    protected $fillable = [
        'name',
        'record_card_number',
        'designation_id',
        'department_id',
        'email',
        'phone',
        'joined_date',
        'status',
        'notes',
    ];

    protected $casts = [
        'joined_date' => 'date',
    ];

    // Relationships
    public function designation()
    {
        return $this->belongsTo(Designation::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

    public function monthlyAttendances()
    {
        return $this->hasMany(MonthlyAttendance::class);
    }

    public function trainingNotifications()
    {
        return $this->hasMany(TrainingNotification::class);
    }

    public function trainingAttendances()
    {
        return $this->hasMany(TrainingAttendance::class);
    }

    public function actions()
    {
        return $this->hasMany(StaffAction::class);
    }

    // Scopes
    public function scopeActive($query)
    {
        return $query->where('status', 'active');
    }

    public function scopeSearch($query, $search)
    {
        return $query->where(function ($q) use ($search) {
            $q->where('name', 'like', "%{$search}%")
              ->orWhere('record_card_number', 'like', "%{$search}%")
              ->orWhere('email', 'like', "%{$search}%");
        });
    }

    // Accessors
    public function getDesignationNameAttribute(): ?string
    {
        return $this->designation?->name;
    }

    public function getDepartmentNameAttribute(): ?string
    {
        return $this->department?->name;
    }

    // Methods
    public function getAttendanceForMonth(int $year, int $month): ?MonthlyAttendance
    {
        return $this->monthlyAttendances()
            ->where('year', $year)
            ->where('month', $month)
            ->first();
    }

    public function getMissedTrainingsCount(int $year, int $month): int
    {
        return $this->trainingAttendances()
            ->whereHas('trainingSession', function ($query) use ($year, $month) {
                $query->whereYear('scheduled_date', $year)
                      ->whereMonth('scheduled_date', $month);
            })
            ->whereIn('attendance_status', ['absent', 'on_leave'])
            ->count();
    }

    public function getLateTrainingsCount(int $year, int $month): int
    {
        return $this->trainingAttendances()
            ->whereHas('trainingSession', function ($query) use ($year, $month) {
                $query->whereYear('scheduled_date', $year)
                      ->whereMonth('scheduled_date', $month);
            })
            ->where('attendance_status', 'late')
            ->count();
    }

    public function getPendingActionsCount(): int
    {
        return $this->actions()->where('status', 'pending')->count();
    }
}
