<?php

namespace App\Models;

use App\Models\Website\Media;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Organization extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'short_name',
        'parent_organization_id',
        'slug',
        'mission',
        'description',
        'logo',
        'image',
        'established_date',
        'status',
        'created_by',
        'updated_by',
    ];

    public function parentOrganization()
    {
        return $this->belongsTo(Organization::class, 'parent_organization_id');
    }

    public function childrenOrganizations()
    {
        return $this->hasMany(Organization::class);
    }

    public function logoMedia()
    {
        return $this->hasOne(Media::class, 'id', 'logo');
    }

    public function imageMedia()
    {
        return $this->hasOne(Media::class, 'id', 'image');
    }

    public function createdBy()
    {
        return $this->belongsTo(User::class, 'created_by');
    }

    public function blogs()
    {
        return $this->hasMany(Blog::class);
    }

    public function events()
    {
        return $this->hasMany(Events::class);
    }
    public function collection()
    {
        return $this->hasMany(Collection::class);
    }
    public function faq()
    {
        return $this->hasMany(Faq::class);
    }
    public function galleryitems()
    {
        return $this->hasMany(Galleryitem::class);
    }
    public function homecarousel()
    {
        return $this->hasMany(Homecarousel::class);
    }
    public function message()
    {
        return $this->hasMany(Message::class);
    }
    public function mission()
    {
        return $this->hasMany(Mission::class);
    }
    public function news()
    {
        return $this->hasMany(News::class);
    }
    public function origanizationstaff()
    {
        return $this->hasMany(Organizationstaff::class);
    }
    public function organizationstaffRole()
    {
        return $this->hasMany(OrganizationstaffRole::class);
    }
    public function service()
    {
        return $this->hasMany(Service::class);
    }
    public function staffRole()
    {
        return $this->hasMany(StaffRole::class);
    }
    public function notice()
    {
        return $this->hasMany(Notice::class);
    }
}