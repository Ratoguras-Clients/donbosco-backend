<?php

namespace App\Models;

use App\Models\Website\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Homemission extends Model
{
    use HasFactory;

    protected $fillable = [
        'organization_id',
        'title',
        'content',
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
     * Get the organization that owns the homemission.
     */
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }

  
    public function media1()
    {
        return $this->belongsTo(Media::class, 'media_id_1');
    }

    
    public function media2()
    {
        return $this->belongsTo(Media::class, 'media_id_2');
    }

    
    public function media3()
    {
        return $this->belongsTo(Media::class, 'media_id_3');
    }

   
    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    
    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }

    
    public function getAllImages()
    {
        return collect([
            $this->media1,
            $this->media2,
            $this->media3,
        ])->filter(); // Remove null values
    }
}