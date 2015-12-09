<?php

namespace Katniss\Models\Helpers;


class Menu
{
    /**
     * @var string
     */
    private $tag;

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $class;

    /**
     * @var array
     */
    private $items;

    /**
     * @var int
     */
    private $itemsNum;

    /**
     * @var string
     */
    private $matchUrl;

    private $matchStrict;

    /**
     * @var string
     */
    private $extraClass;

    /**
     * @param string $tag
     * @param string $class
     * @param string $match_url
     * @param string $extra_class
     */
    public function __construct($tag = 'ul', $class = '', $match_url = '', $id = '', $extra_class = 'active')
    {
        $this->tag = $tag;
        $this->id = empty($id) ? '' : ' id="' . trim($id) . '"';
        $this->class = empty($class) ? '' : ' class="' . trim($class) . '"';
        $this->items = array();
        $this->itemsNum = 0;
        $this->matchUrl = $match_url;
        $this->matchStrict = false;
        $this->extraClass = $extra_class;
    }

    public function restrictMatching($strict = false)
    {
        $this->matchStrict = $strict;
    }

    /**
     * @param string $route
     * @param string $lang_index
     * @param bool $active
     * @param string $before
     * @param string $after
     * @return \Katniss\Models\Helpers\Menu
     */
    public function addItem(MenuItem $item)
    {
        $item->parent = $this;
        $this->items[] = $item;
        ++$this->itemsNum;

        return $this;
    }

    /**
     * @param integer $index
     * @return MenuItem
     */
    public function getItem($index)
    {
        if ($index < 0 || $index > $this->itemsNum) {
            return null;
        }

        return $this->items[$index];
    }

    /**
     * @return \Katniss\Models\Helpers\MenuItem
     */
    public function last()
    {
        return $this->itemsNum > 0 ? $this->items[$this->itemsNum - 1] : null;
    }

    /**
     * @return string
     */
    public function render()
    {
        $rendered_items = '';
        foreach ($this->items as $item) {
            $rendered_items .= $item->render($this->matchUrl, $this->extraClass, $this->matchStrict);
        }

        return sprintf('<%s%s%s role="menu">%s</%s>',
            $this->tag,
            $this->id,
            $this->class,
            $rendered_items,
            $this->tag
        );
    }
}
