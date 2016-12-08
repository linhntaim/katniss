<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-12-06
 * Time: 20:23
 */

namespace Katniss\Everdeen\Utils\DataStructure\Menu;


class Menu
{
    protected $data;

    protected $currentIndex;

    protected $matchingUrl;
    protected $strict;

    public function __construct($matchingUrl = null, $strict = true)
    {
        $this->matchingUrl = $matchingUrl;
        $this->strict = $strict;
        $this->reset();
    }

    public function reset()
    {
        $this->data = [];
        $this->currentIndex = -1;
    }

    public function get()
    {
        return $this->data;
    }

    protected function matchUrl($url)
    {
        if (empty($this->matchingUrl)) return false;
        if ($this->strict && $url == $this->matchingUrl) {
            return true;
        }

        return $url == $this->matchingUrl
            || (notRootUrl($url) && beginsWith($this->matchingUrl, $url));
    }

    public function add($url, $name, $before = '', $after = '', $itemClass = '', $linkClass = '', $itemId = '', $title = '')
    {
        $this->data[] = [
            'item' => [
                'title' => empty($title) ? $name : $title,
                'url' => $url,
                'name' => $name,
                'before' => $before,
                'after' => $after,
                'item_class' => $itemClass,
                'link_class' => $linkClass,
                'item_id' => $itemId,
                'active' => $this->matchUrl($url),
            ],
        ];
        $this->currentIndex = count($this->data) - 1;
    }

    public function addSubMenu(Menu $menu)
    {
        $this->data[$this->currentIndex]['children'] = $menu->get();
    }
}