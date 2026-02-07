<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Spatie\MediaLibrary\HasMedia;
use Spatie\MediaLibrary\InteractsWithMedia;

class Food extends Model implements HasMedia
{
    use InteractsWithMedia;

    protected $fillable = [
        'name',
        'price',
        'description',
    ];

    protected $casts = [
        'price' => 'integer',
    ];

    protected $appends = ['images'];

    protected $hidden = ['media'];

    public function registerMediaCollections(): void
    {
        $this->addMediaCollection('images')
            ->acceptsMimeTypes(['image/jpeg', 'image/jpg', 'image/png', 'image/webp'])
            ->useDisk('public');
    }

    public function getImagesAttribute()
    {
        $media = $this->getMedia('images');
        return $media->map(function ($item) {
            return [
                'file_name' => $item->file_name,
                'file_type' => $item->mime_type,
                'file_size' => $item->size,
                'url' => $item->getUrl(),
            ];
        });
    }
}
