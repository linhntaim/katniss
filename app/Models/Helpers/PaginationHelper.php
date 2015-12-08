<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-09-16
 * Time: 02:17
 */

namespace Katniss\Models\Helpers;


use Guzzle\Http\QueryString;

class PaginationHelper
{
    public $last;
    public $first;
    public $next;
    public $prev;
    public $start;
    public $end;
    public $current;
    public $atFirst;
    public $atLast;
    public $startOrder;

    public function __construct($last, $current, $itemsPerPage = 0, $max_page_show = AppConfig::DEFAULT_PAGINATION_ITEMS)
    {
        $this->startOrder = ($current - 1) * $itemsPerPage;
        $pivot = round($max_page_show / 2);
        $distance = floor($max_page_show / 2);
        $this->last = $last;
        $this->first = 1;
        $this->prev = $current > $this->first ? $current - 1 : $this->first;
        $this->next = $current < $last ? $current + 1 : $last;
        $this->end = $current < $pivot ? ($last > $max_page_show ? $max_page_show : $last) : ($current < $last - $distance ? $current + $distance : $last);
        $this->start = $this->end - $max_page_show + $this->first;
        if ($this->start < $this->first) {
            $this->start = $this->first;
        }
        $this->current = $current;
        $this->atFirst = $this->current == $this->first;
        $this->atLast = $this->current == $this->last;
    }

    public function render(QueryString $queryString, $htmlWrapTag = 'ul|pagination', $htmlItemTag = 'li|item', $activeCssClass = 'active')
    {
        $wrapTagParts = explode('|', $htmlWrapTag);
        $wrapTag = $wrapTagParts[0];
        $wrapCssClass = empty($wrapTagParts[1]) ? '' : ' class="' . $wrapTagParts[1] . '"';
        $itemTagParts = explode('|', $htmlItemTag);
        $itemTag = $itemTagParts[0];
        $itemCssClass = empty($itemTagParts[1]) ? '' : ' class="' . $itemTagParts[1] . '"';

        $output = '<' . $wrapTag . $wrapCssClass . '>';
        $output .= '</' . $wrapTag . '>';
    }
}