<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Website\Media;

class Collection extends Model
{
    protected $fillable = [
        'organization_id',
        'title',
        'description',
        'order_index',
        'cover_image',
        'is_active',
        'created_by',
        'updated_by',
    ];
    protected $casts = [
        'is_active' => 'boolean',
    ];
    public function organization()
    {
        return $this->belongsTo(Organization::class, 'organization_id');
    }
    public function galleryItems()
    {
        return $this->hasMany(GalleryItem::class, 'collection_id')->orderBy('order_index');
    }

    public function creator()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function updater()
    {
        return $this->belongsTo(User::class, 'updated_by');
    }
    public function media()
{
    return $this->belongsTo(Media::class, 'media_id');
}
    public function coverItem()
    {
        return $this->hasOne(GalleryItem::class, 'collection_id')
            ->where('is_active', true)
            ->orderBy('order_index');
    }

}
