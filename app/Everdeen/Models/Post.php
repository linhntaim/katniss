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
use Katniss\Everdeen\ModelTraits\ExtendTranslatableTrait;

class Post extends Model
{
    const TYPE_PAGE = 0;
    const TYPE_ARTICLE = 1;
    const TYPE_HELP = 2;

    use Translatable;
    use ExtendTranslatableTrait;
    public $useTranslationFallback = true;

    protected $table = 'posts';
    protected $fillable = ['user_id', 'viewed', 'template', 'featured_image', 'type',
        'title', 'slug', 'description', 'content', 'raw_content'];

    protected $translationForeignKey = 'post_id';
    public $translatedAttributes = ['title', 'slug', 'description', 'content', 'raw_content'];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function scopeOfPage($query)
    {
        return $query->where('type', self::TYPE_PAGE);
    }

    public function scopeOfArticle($query)
    {
        return $query->where('type', self::TYPE_ARTICLE);
    }

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'categories_posts', 'post_id', 'category_id');
    }
}