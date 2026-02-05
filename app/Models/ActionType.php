<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ActionType extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'code',
        'description',
        'template',
        'icon',
        'color',
        'requires_document',
        'requires_feedback',
        'is_active',
        'sort_order',
    ];

    protected $casts = [
        'requires_document' => 'boolean',
        'requires_feedback' => 'boolean',
        'is_active' => 'boolean',
    ];

    // Relationships
    public function triggers()
    {
        return $this->hasMany(ActionTrigger::class);
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

    public function scopeOrdered($query)
    {
        return $query->orderBy('sort_order');
    }

    // Accessors
    public function getBadgeColorAttribute(): string
    {
        return $this->color ?? 'gray';
    }

    public function getIconClassAttribute(): string
    {
        return $this->icon ?? 'heroicon-o-exclamation-circle';
    }

    // Static methods
    public static function getByCode(string $code): ?self
    {
        return static::where('code', $code)->first();
    }

    public static function warningLetter(): ?self
    {
        return static::getByCode('warning_letter');
    }

    public static function meeting(): ?self
    {
        return static::getByCode('meeting');
    }

    public static function trainingAssignment(): ?self
    {
        return static::getByCode('training_assignment');
    }
}
