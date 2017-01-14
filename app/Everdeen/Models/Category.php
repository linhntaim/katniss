<?php

namespace Katniss\Everdeen\Models;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    const TYPE_LINK = 0;
    const TYPE_ARTICLE = 1;
    const TYPE_MEDIA = 2;
    const TYPE_HELP = 3;

    use Translatable;
    public $useTranslationFallback = true;

    protected $table = 'categories';
    protected $fillable = ['parent_id', 'order', 'type', 'name', 'slug', 'description'];

    protected $translationForeignKey = 'category_id';
    public $translatedAttributes = ['name', 'slug', 'description'];

    public function parent()
    {
        return $this->hasOne(Category::class, 'id', 'parent_id');
    }

    public function scopeOfLink($query)
    {
        return $query->where('type', $this::TYPE_LINK);
    }

    public function links()
    {
        return $this->belongsToMany(Link::class, 'categories_links', 'category_id', 'link_id');
    }

    public function getOrderedLinksAttribute()
    {
        return $this->links()->orderBy('order', 'asc')->get();
    }

    public function posts()
    {
        return $this->belongsToMany(Post::class, 'categories_posts', 'category_id', 'post_id');
    }

    public function getOrderedPostsAttribute()
    {
        return $this->media()->orderBy('order', 'asc')->get();
    }

    public function media()
    {
        return $this->belongsToMany(Media::class, 'categories_media', 'category_id', 'media_id');
    }

    public function getOrderedMediaAttribute()
    {
        return $this->media()->orderBy('order', 'asc')->get();
    }
}