<?php

namespace App\Models;

use App\Models\Website\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Aboutstory extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'title',
        'content',
        'features',
        'media_id_1',
        'media_id_2',
        'media_id_3',
        'is_home',
        'created_by',
        'updated_by',
    ];

    protected $casts = [
        'is_home' => 'boolean',
    ];

    /**
     * Get the organization that owns the aboutstory.
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

    /**
     * Get the first media/image.
     */
    public function media1()
    {
        return $this->belongsTo(Media::class, 'media_id_1');
    }

    /**
     * Get the second media/image.
     */
    public function media2()
    {
        return $this->belongsTo(Media::class, 'media_id_2');
    }

    /**
     * Get the third media/image.
     */
    public function media3()
    {
        return $this->belongsTo(Media::class, 'media_id_3');
    }

    /**
     * Get the user who created the aboutstory.
     */
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    /**
     * Get the user who last updated the aboutstory.
     */
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    /**
     * Get all images as a collection (helper method).
     */
    public function getAllImages()
    {
        return collect([
            $this->media1,
            $this->media2,
            $this->media3,
        ])->filter(); // Remove null values
    }
}