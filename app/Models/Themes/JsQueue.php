<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2016-08-14
 * Time: 19:32
 */

namespace Katniss\Models\Themes;


use Katniss\Models\Helpers\HtmlTag\Html5;

class JsQueue extends AssetQueue
{
    const LIB_JQUERY_NAME = 'jquery';
    const LIB_JQUERY_UI_NAME = 'jquery-ui';
    const LIB_BOOTSTRAP_NAME = 'bootstrap';
    const LIB_JQUERY_UI_BOOTSTRAP_NAME = 'jquery-ui-bootstrap';

    const TYPE_RAW = 'raw';
    const TYPE_VAR = 'var';

    public function __construct()
    {
        parent::__construct();
    }

    public function add($name, $content, $type = AssetQueue::TYPE_DEFAULT)
    {
        if ($name == self::LIB_BOOTSTRAP_NAME && $this->existed(self::LIB_JQUERY_UI_NAME)) {
            parent::add(
                self::LIB_JQUERY_UI_BOOTSTRAP_NAME,
                _kExternalLink(self::LIB_JQUERY_UI_BOOTSTRAP_NAME),
                self::TYPE_RAW
            );
        }

        parent::add($name, $content, $type);
    }

    public function flush($echo = true)
    {
        $this->output = [];
        foreach ($this->queue as $name => $item) {
            switch ($item['type']) {
                case self::TYPE_RAW:
                    $this->output[] = Html5::jsInline($item['content']);
                    break;
                case self::TYPE_VAR:
                    $content = [];
                    foreach ($item['content'] as $varName => $varValue) {
                        $content[] = 'var ' . $varName . ' = ' . $this->getVarValue($varValue) . ';';
                    }
                    $this->output[] = Html5::jsInline(implode(PHP_EOL, $content));
                    break;
                default:
                    $this->output[] = Html5::js($item['content']);
                    break;
            }
        }

        return parent::flush($echo);
    }

    private function getVarValue($value)
    {
        if (is_string($value)) {
            return "'" . $value . "'";
        } elseif (is_array($value)) {
            if (array_keys($value) !== range(0, count($value) - 1)) {
                return $this->getVarValue(json_encode($value));
            } else {
                $p = [];
                foreach ($value as $item) {
                    $p[] = $this->getVarValue($value);
                }
                return '[' . implode(',', $p) . ']';
            }
        } elseif (is_object($value)) {
            return $this->getVarValue(json_encode($value));
        }

        return $value;
    }
}