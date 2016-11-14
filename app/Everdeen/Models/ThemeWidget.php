<?php

namespace Katniss\Everdeen\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Query\Builder;
use Katniss\Everdeen\Themes\Widget;

class ThemeWidget extends Model
{
    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table = 'theme_widgets';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['widget_name', 'theme_name', 'placeholder', 'constructing_data', 'active', 'order'];

    public function getParamsAttribute()
    {
        if (empty($this->attributes['constructing_data'])) return [];

        $params = json_decode($this->attributes['constructing_data'], true);
        if ($params === false) return [];

        return $params;
    }

    public function getHasThemeAttribute()
    {
        return !empty($this->attributes['theme_name']);
    }

    public function getNameAttribute()
    {
        return $this->attributes['widget_name'];
    }

    public function getThemeAttribute()
    {
        return $this->attributes['theme_name'];
    }

    /**
     * @param Builder $query
     * @param string $placeholder
     * @return Builder
     */
    public function scopeForDisplay($query, $placeholder = '')
    {
        if (!empty($placeholder)) {
            $query->where('placeholder', $placeholder);
        }
        $query->where('active', true);

        return $query;
    }

    /**
     * @param Builder $query
     * @param array $placeholders
     * @return Builder
     */
    public function scopeCheckPlaceholders($query, array $placeholders)
    {
        $query->whereIn('placeholder', $placeholders);
        return $query;
    }

    /**
     * @param Builder $query
     * @param array $widgets
     * @return Builder
     */
    public function scopeCheckWidgets($query, array $widgets)
    {
        $query->whereIn('widget_name', $widgets);
        return $query;
    }

    public function render()
    {
        return Widget::doRender($this);
    }

    public function register()
    {
        Widget::doRegister($this);
    }
}
