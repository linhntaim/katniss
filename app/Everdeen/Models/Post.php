<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-04
 * Time: 17:09
 */

namespace Katniss\Everdeen\Models;


use Dimsav\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    const UNKNOWN = 0;
    const PAGE = 1;
    const ARTICLE = 2;
    const FAQ = 3;

    use Translatable;
    public $useTranslationFallback = true;

    protected $table = 'posts';
    protected $fillable = ['user_id', 'template', 'featured_image', 'type', 'title', 'slug', 'description', 'content'];

    protected $translationForeignKey = 'post_id';
    public $translatedAttributes = ['title', 'slug', 'description', 'content'];

    public function author()
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }

    public function scopeOfPage($query)
    {
        return $query->where('type', $this::PAGE);
    }

    public function scopeOfArticle($query)
    {
        return $query->where('type', $this::ARTICLE);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'categories_posts', 'post_id', 'category_id');
    }
}