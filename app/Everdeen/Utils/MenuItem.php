<?php

namespace Katniss\Everdeen\Utils;


class MenuItem
{
    /**
     * @var string
     */
    private $href;

    /**
     * @var string
     */
    private $url;

    /**
     * @var string
     */
    private $label;

    /**
     * @var string
     */
    private $tag;

    /**
     * @var string
     */
    private $role;

    /**
     * @var string
     */
    private $id;

    /**
     * @var string
     */
    private $title;

    /**
     * @var string
     */
    private $class;

    /**
     * @var string
     */
    private $linkClass;

    /**
     * @var string
     */
    private $before;

    /**
     * @var string
     */
    private $after;

    /**
     * @var \Katniss\Everdeen\Utils\Menu
     */
    private $menu;

    /**
     * @var \Katniss\Everdeen\Utils\Menu
     */
    public $parent;

    /**
     * @param string $route
     * @param string $lang_index
     * @param string $tag
     * @param string $class
     * @param string $link_class
     * @param string $before
     * @param string $after
     */
    public function __construct($url, $label, $tag = 'li', $class = '', $link_class = '', $before = '', $after = '', $id = '', $title = '')
    {
        $this->href = empty($url) ? null : ' href="' . $url . '"';
        $this->url = $url;
        $this->label = $label;
        $this->tag = $tag;
        $this->role = '';
        $this->id = empty($id) ? '' : ' id="' . trim($id) . '"';
        $this->title = empty($title) ? '' : ' title="' . trim($title) . '"';
        $this->class = $class;
        $this->linkClass = $link_class;
        $this->before = $before;
        $this->after = $after;
    }

    /**
     * @param string $url
     * @param string $extraClass
     * @return \Katniss\Everdeen\Utils\MenuItem
     */
    public function matchUrl($match_url, $extraClass = 'active', $strict = false)
    {
        if ($this->href != null) {
            if (!$strict) {
                if ($this->url == $match_url || (notRootUrl($this->url) && beginsWith($match_url, $this->url))) {
                    $this->class .= ' ' . $extraClass;
                }
            } else {
                if ($this->url == $match_url) {
                    $this->class .= ' ' . $extraClass;
                }
            }
        }

        return $this;
    }

    /**
     * @param string $dropDownClass
     * @param string $toggleClass
     * @param string $extra
     * @return \Katniss\Everdeen\Utils\MenuItem
     */
    public function enableDropDown($dropDownClass = 'dropdown', $toggleClass = 'dropdown-toggle', $extra = 'data-toggle="dropdown')
    {
        if ($this->href != null) {
            $this->class .= ' ' . $dropDownClass;
            $this->linkClass .= ' ' . $toggleClass . (empty($extra) ? '' : '" ' . $extra);
        } elseif ($this->class == 'divider') {
            $this->role = ' role="separator"';
        }

        return $this;
    }

    /**
     * @param \Katniss\Everdeen\Utils\Menu $menu
     */
    public function setChildMenu(Menu $menu)
    {
        $this->menu = $menu;
    }

    /**
     * @return string
     */
    private function menuRender()
    {
        return isset($this->menu) ? $this->menu->render() : '';
    }

    /**
     * @return string
     */
    public function render($match_url = '', $extraClass = 'active', $strict = false)
    {
        if ($this->href == null) {
            return sprintf('<%s%s%s%s%s>%s%s%s%s</%s>',
                $this->tag,
                $this->role,
                $this->id,
                $this->title,
                empty($this->class) ? '' : ' class="' . trim($this->class) . '"',
                $this->before,
                $this->label,
                $this->after,
                $this->menuRender(),
                $this->tag
            );
        }

        if (!empty($match_url)) {
            $this->matchUrl($match_url, $extraClass, $strict);
        }

        return sprintf('<%s%s%s%s%s><a%s%s>%s%s%s</a>%s</%s>',
            $this->tag,
            $this->role,
            $this->id,
            $this->title,
            empty($this->class) ? '' : ' class="' . trim($this->class) . '"',
            empty($this->linkClass) ? '' : ' class="' . trim($this->linkClass) . '"',
            $this->href,
            $this->before,
            $this->label,
            $this->after,
            $this->menuRender(),
            $this->tag
        );
    }
}
