<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-11-23
 * Time: 23:37
 */

namespace Katniss\Models\Themes\Plugins\ExtraHtml;

use Katniss\Models\Themes\Plugins\DefaultWidget\Widget as DefaultWidget;
use Katniss\Models\Themes\HomeThemes\HomeThemeFacade;

class Widget extends DefaultWidget
{
    const WIDGET_NAME = 'extra_html';
    const WIDGET_DISPLAY_NAME = 'Extra HTML';

    public $content;

    public function __init()
    {
        parent::__init(); // TODO: Change the autogenerated stub

        $this->content = '';
        if (!empty($this->localizedData)) {
            $this->content = defPr($this->localizedData['content'], '');
        }
    }

    public function viewAdminParams()
    {
        return array_merge(parent::viewAdminParams(), [
            'extended_localizing_path' => HomeThemeFacade::commonWidget($this::WIDGET_NAME, 'admin_localizing')
        ]);
    }

    public function render()
    {
        return '<div id="' . $this->getHtmlId() . '" class="widget-extra-html">' . $this->content . '</div>';
    }

    public function localizedFields()
    {
        return array_merge(parent::localizedFields(), [
            'content',
        ]);
    }
}