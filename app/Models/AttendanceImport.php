<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AttendanceImport extends Model
{
    use HasFactory;

    protected $fillable = [
        'file_name',
        'original_file_name',
        'year',
        'month',
        'imported_by',
        'records_count',
        'success_count',
        'error_count',
        'status',
        'error_log',
    ];

    protected $casts = [
        'error_log' => 'array',
    ];

    // Relationships
    public function importer()
    {
        return $this->belongsTo(User::class, 'imported_by');
    }

    public function monthlyAttendances()
    {
        return $this->hasMany(MonthlyAttendance::class, 'import_batch_id');
    }

    // Scopes
    public function scopeCompleted($query)
    {
        return $query->where('status', 'completed');
    }

    public function scopeFailed($query)
    {
        return $query->where('status', 'failed');
    }

    public function scopeForPeriod($query, int $year, int $month)
    {
        return $query->where('year', $year)->where('month', $month);
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

    public function getStatusBadgeClassAttribute(): string
    {
        return match($this->status) {
            'completed' => 'bg-green-100 text-green-800',
            'processing' => 'bg-yellow-100 text-yellow-800',
            'failed' => 'bg-red-100 text-red-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    public function getHasErrorsAttribute(): bool
    {
        return $this->error_count > 0 || !empty($this->error_log);
    }

    // Methods
    public function markAsProcessing(): void
    {
        $this->update(['status' => 'processing']);
    }

    public function markAsCompleted(int $successCount, int $errorCount): void
    {
        $this->update([
            'status' => 'completed',
            'success_count' => $successCount,
            'error_count' => $errorCount,
        ]);
    }

    public function markAsFailed(array $errors): void
    {
        $this->update([
            'status' => 'failed',
            'error_log' => $errors,
        ]);
    }

    public function addError(string $error): void
    {
        $errors = $this->error_log ?? [];
        $errors[] = $error;
        $this->update(['error_log' => $errors]);
    }
}
