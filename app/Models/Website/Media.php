<?php

namespace App\Models\Website;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\MorphTo;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Media extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'medias';
    protected $fillable = [
        'filename',
        'disk',
        'filepath',
        'mime_type',
        'filesize',
        'alt_text',
        'description',
        'mediable_type',
        'mediable_id',
        'uploader_id',
        'deleted_at',
    ];

    protected $casts = [
        'filesize' => 'integer',
    ];

    /**
     * Get the parent model that the media belongs to (polymorphic relation).
     */
    public function mediable(): MorphTo
    {
        return $this->morphTo();
    }

    protected $appends = ['url', 'extension'];

    /**
     * Get the user who uploaded the media.
     */
    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'uploader_id');
    }

    /**
     * Get the full URL to the media file.
     */
    public function getUrlAttribute(): string
    {
        return url('/') . '/' . $this->filepath;
    }

    /**
     * Get the file extension.
     */
    public function getExtensionAttribute(): string
    {
        return pathinfo($this->filepath, PATHINFO_EXTENSION);
    }

    /**
     * Determine if the media is an image.
     */
    public function isImage(): bool
    {
        return Str::startsWith($this->mime_type, 'image/');
    }

    /**
     * Determine if the media is a video.
     */
    public function isVideo(): bool
    {
        return Str::startsWith($this->mime_type, 'video/');
    }

    /**
     * Determine if the media is a document.
     */
    public function isDocument(): bool
    {
        return Str::startsWith($this->mime_type, 'application/') || Str::startsWith($this->mime_type, 'text/');
    }
}
