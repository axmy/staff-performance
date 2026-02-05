<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TrainingNotification extends Model
{
    use HasFactory;

    protected $fillable = [
        'training_session_id',
        'staff_id',
        'notified_at',
        'notification_method',
        'response',
        'response_at',
        'response_notes',
        'notified_by',
    ];

    protected $casts = [
        'notified_at' => 'datetime',
        'response_at' => 'datetime',
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

    public function notifier()
    {
        return $this->belongsTo(User::class, 'notified_by');
    }

    // Scopes
    public function scopeNotified($query)
    {
        return $query->whereNotNull('notified_at');
    }

    public function scopePendingResponse($query)
    {
        return $query->where('response', 'no_response');
    }

    public function scopeConfirmed($query)
    {
        return $query->where('response', 'confirmed');
    }

    // Accessors
    public function getIsNotifiedAttribute(): bool
    {
        return $this->notified_at !== null;
    }

    public function getHasRespondedAttribute(): bool
    {
        return $this->response !== 'no_response';
    }

    public function getResponseBadgeClassAttribute(): string
    {
        return match($this->response) {
            'confirmed' => 'bg-green-100 text-green-800',
            'declined' => 'bg-red-100 text-red-800',
            'tentative' => 'bg-yellow-100 text-yellow-800',
            default => 'bg-gray-100 text-gray-800',
        };
    }

    // Methods
    public function markAsNotified(string $method, ?int $notifiedBy = null): void
    {
        $this->update([
            'notified_at' => now(),
            'notification_method' => $method,
            'notified_by' => $notifiedBy,
        ]);
    }

    public function setResponse(string $response, ?string $notes = null): void
    {
        $this->update([
            'response' => $response,
            'response_at' => now(),
            'response_notes' => $notes,
        ]);
    }
}
