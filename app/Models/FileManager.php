<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Support\Facades\Storage;

class FileManager extends Model
{
    use HasFactory;

    protected $fillable = [
        'original_name',
        'file_name',
        'path',
        'disk',
        'mime_type',
        'size',
        'uploaded_by',
    ];

    protected function casts(): array
    {
        return [
            'size' => 'integer',
        ];
    }

    public function uploader(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploaded_by');
    }

    public function getUrlAttribute(): ?string
    {
        if ($this->disk !== 'public') {
            return null;
        }

        return Storage::disk($this->disk)->url($this->path);
    }

    public function getIsImageAttribute(): bool
    {
        return str_starts_with((string) $this->mime_type, 'image/');
    }

    public function getIsPdfAttribute(): bool
    {
        return $this->mime_type === 'application/pdf';
    }

    public function getIsTextAttribute(): bool
    {
        return str_starts_with((string) $this->mime_type, 'text/')
            || in_array($this->mime_type, [
                'application/json',
                'application/xml',
                'application/x-httpd-php',
                'application/javascript',
            ], true);
    }

    public function getExtensionAttribute(): string
    {
        return strtoupper(pathinfo($this->original_name, PATHINFO_EXTENSION) ?: 'FILE');
    }

    public function getIconAttribute(): string
    {
        if ($this->is_pdf) {
            return 'fa-file-pdf text-danger';
        }

        if ($this->is_text) {
            return 'fa-file-lines text-info';
        }

        if (str_contains((string) $this->mime_type, 'zip') || str_contains((string) $this->mime_type, 'compressed')) {
            return 'fa-file-zipper text-warning';
        }

        if (str_contains((string) $this->mime_type, 'word')) {
            return 'fa-file-word text-primary';
        }

        if (str_contains((string) $this->mime_type, 'excel') || str_contains((string) $this->mime_type, 'spreadsheet')) {
            return 'fa-file-excel text-success';
        }

        return 'fa-file text-secondary';
    }

    public function getHumanSizeAttribute(): string
    {
        $bytes = $this->size;
        $units = ['B', 'KB', 'MB', 'GB', 'TB'];

        for ($i = 0; $bytes >= 1024 && $i < count($units) - 1; $i++) {
            $bytes /= 1024;
        }

        return round($bytes, $i === 0 ? 0 : 2).' '.$units[$i];
    }
}
