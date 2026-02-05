<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Storage;

class ActionDocument extends Model
{
    use HasFactory;

    protected $fillable = [
        'staff_action_id',
        'document_type',
        'title',
        'file_path',
        'file_name',
        'mime_type',
        'file_size',
        'uploaded_by',
    ];

    // Relationships
    public function staffAction()
    {
        return $this->belongsTo(StaffAction::class);
    }

    public function uploader()
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    // Accessors
    public function getDocumentTypeLabelAttribute(): string
    {
        return match($this->document_type) {
            'warning_letter' => 'Warning Letter',
            'meeting_notes' => 'Meeting Notes',
            'report' => 'Report',
            'acknowledgment' => 'Acknowledgment',
            default => 'Other',
        };
    }

    public function getFileSizeFormattedAttribute(): string
    {
        $bytes = $this->file_size;
        if ($bytes >= 1048576) {
            return round($bytes / 1048576, 2) . ' MB';
        } elseif ($bytes >= 1024) {
            return round($bytes / 1024, 2) . ' KB';
        }
        return $bytes . ' B';
    }

    public function getUrlAttribute(): string
    {
        return Storage::url($this->file_path);
    }

    public function getIsImageAttribute(): bool
    {
        return str_starts_with($this->mime_type ?? '', 'image/');
    }

    public function getIsPdfAttribute(): bool
    {
        return $this->mime_type === 'application/pdf';
    }

    // Methods
    public function download()
    {
        return Storage::download($this->file_path, $this->file_name);
    }

    public function deleteFile(): bool
    {
        if (Storage::exists($this->file_path)) {
            return Storage::delete($this->file_path);
        }
        return true;
    }
}
