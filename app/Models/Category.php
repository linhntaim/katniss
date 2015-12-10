<?php

namespace Katniss\Models;

use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
    const UNKNOWN = 0;
    const LINK = 1;

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
        return $query->where('type', $this::LINK);
    }

    public function links()
    {
        return $this->belongsToMany(Link::class, 'categories_links', 'category_id', 'link_id')
            ->orderBy('order', 'asc');
    }
}

class CategoryTranslation extends Model
{
    public $timestamps = false;

    protected $table = 'category_translations';
    protected $fillable = ['name', 'slug', 'description'];
}