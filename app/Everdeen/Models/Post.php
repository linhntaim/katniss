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
    const TYPE_PAGE = 0;
    const TYPE_ARTICLE = 1;

    use Translatable;
    public $useTranslationFallback = true;

    protected $table = 'posts';
    protected $fillable = ['user_id', 'template', 'featured_image', 'type', 'title', 'slug', 'description', 'content', 'raw_content'];

    protected $translationForeignKey = 'post_id';
    public $translatedAttributes = ['title', 'slug', 'description', 'content', 'raw_content'];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function scopeOfPage($query)
    {
        return $query->where('type', $this::TYPE_PAGE);
    }

    public function scopeOfArticle($query)
    {
        return $query->where('type', $this::TYPE_ARTICLE);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'categories_posts', 'post_id', 'category_id');
    }
}