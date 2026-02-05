<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MonthlyAttendance extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_id',
        'year',
        'month',
        'days_present',
        'days_absent',
        'days_leave',
        'days_late',
        'total_working_days',
        'import_batch_id',
        'is_processed',
    ];

    protected $casts = [
        'is_processed' => 'boolean',
    ];

    // Relationships
    public function staff()
    {
        return $this->belongsTo(Staff::class);
    }

    public function importBatch()
    {
        return $this->belongsTo(AttendanceImport::class, 'import_batch_id');
    }

    // Scopes
    public function scopeForPeriod($query, int $year, int $month)
    {
        return $query->where('year', $year)->where('month', $month);
    }

    public function scopeForYear($query, int $year)
    {
        return $query->where('year', $year);
    }

    public function scopeProcessed($query)
    {
        return $query->where('is_processed', true);
    }

    public function scopeUnprocessed($query)
    {
        return $query->where('is_processed', false);
    }

    // Accessors
    public function getMonthNameAttribute(): string
    {
        return date('F', mktime(0, 0, 0, $this->month, 1));
    }

    public function getPeriodAttribute(): string
    {
        return $this->month_name . ' ' . $this->year;
    }

    public function getAttendancePercentageAttribute(): float
    {
        if ($this->total_working_days === 0) return 0;
        return round(($this->days_present / $this->total_working_days) * 100, 1);
    }

    public function getAbsencePercentageAttribute(): float
    {
        if ($this->total_working_days === 0) return 0;
        return round(($this->days_absent / $this->total_working_days) * 100, 1);
    }

    public function getLatePercentageAttribute(): float
    {
        if ($this->total_working_days === 0) return 0;
        return round(($this->days_late / $this->total_working_days) * 100, 1);
    }

    public function getStatusAttribute(): string
    {
        $absenceRate = $this->absence_percentage;
        $lateRate = $this->late_percentage;

        if ($absenceRate >= 20 || $lateRate >= 30) {
            return 'critical';
        } elseif ($absenceRate >= 10 || $lateRate >= 20) {
            return 'warning';
        }
        return 'good';
    }

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'critical' => 'bg-red-100 text-red-800',
            'warning' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-green-100 text-green-800',
        };
    }

    // Methods
    public function markAsProcessed(): void
    {
        $this->update(['is_processed' => true]);
    }

    public function exceedsThreshold(string $field, int $threshold): bool
    {
        return match($field) {
            'absent_days' => $this->days_absent >= $threshold,
            'late_days' => $this->days_late >= $threshold,
            'leave_days' => $this->days_leave >= $threshold,
            default => false,
        };
    }
}
