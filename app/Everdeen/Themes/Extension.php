<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-11-16
 * Time: 11:46
 */

namespace Katniss\Everdeen\Themes;

use Katniss\Everdeen\Themes\HomeThemes\HomeThemeFacade;

class Extension
{
    const EXTENSION_NAME = '';
    const EXTENSION_DISPLAY_NAME = '';
    const EXTENSION_DESCRIPTION = '';
    const EXTENSION_EDITABLE = false;
    const EXTENSION_TRANSLATABLE = false;
    const THEME_NAME = '';

    public function EXTENSION_OPTION()
    {
        return 'extension_' . $this::EXTENSION_NAME;
    }

    /**
     * @var array
     */
    private static $sharedData = [];

    /**
     * @return array|null
     */
    public static function getSharedData($extensionName)
    {
        if (isset(self::$sharedData[$extensionName])) {
            return self::$sharedData[$extensionName];
        }

        return null;
    }

    /**
     * @param array $properties
     */
    public function makeSharedData(array $properties)
    {
        $data = new \stdClass();
        foreach ($properties as $name => $value) {
            if (is_int($name)) {
                if (isset($this->{$value})) {
                    $data->{$value} = $this->{$value};
                }
            } elseif (is_string($name)) {
                $data->{$name} = $value;
            }
        }
        self::$sharedData[$this::EXTENSION_NAME] = $data;
    }

    /**
     * @var array
     */
    protected $data;

    /**
     * @var array
     */
    protected $localizedData;

    /**
     * @var array
     */
    protected $params;

    public function __construct()
    {
        if ($this::EXTENSION_EDITABLE) {
            $this->data = (array)getOption($this->EXTENSION_OPTION(), []);
            $this->params = [];

            $this->__init();
        }
    }

    public function isEditable()
    {
        return $this::EXTENSION_EDITABLE;
    }

    public function isTranslatable()
    {
        return $this::EXTENSION_TRANSLATABLE;
    }

    public function getName()
    {
        return $this::EXTENSION_NAME;
    }

    public function getDisplayName()
    {
        return $this::EXTENSION_DISPLAY_NAME;
    }

    public function getDescription()
    {
        return $this::EXTENSION_DESCRIPTION;
    }

    public function getTheme()
    {
        return $this::THEME_NAME;
    }

    public function getProperty($name, $locale = '')
    {
        if (!$this::EXTENSION_EDITABLE) abort(404);

        if (empty($locale) || !$this::EXTENSION_TRANSLATABLE) {
            return !empty($this->data[$name]) ? $this->data[$name] :
                (!empty($this->localizedData[$name]) ? $this->localizedData[$name] : '');
        }

        if (!isset($this->data[$locale])) return '';

        return !empty($this->data[$locale][$name]) ? $this->data[$locale][$name] : '';
    }

    public function register()
    {
    }

    protected function __init()
    {
        if (!$this::EXTENSION_EDITABLE) abort(404);

        if ($this::EXTENSION_TRANSLATABLE) {
            $locale = currentLocaleCode();
            $fallbackLocale = config('app.fallback_locale');

            $this->localizedData = null;
            if (!empty($this->data[$locale])) {
                $this->localizedData = $this->data[$locale];
            } elseif (!empty($this->data[$fallbackLocale])) {
                $this->localizedData = $this->data[$fallbackLocale];
            }
        }
    }

    public function viewAdmin()
    {
        if (!$this::EXTENSION_EDITABLE) abort(404);

        return empty($this::THEME_NAME) ? HomeThemeFacade::commonAdminExtension($this::EXTENSION_NAME) : HomeThemeFacade::adminExtension($this::EXTENSION_NAME);
    }

    public function viewAdminParams()
    {
        return [];
    }

    public function validationRules()
    {
        if (!$this::EXTENSION_EDITABLE) abort(404);

        return [];
    }

    public function localizedValidationRules()
    {
        if (!$this::EXTENSION_EDITABLE || !$this::EXTENSION_TRANSLATABLE) abort(404);

        return [];
    }

    public function fields()
    {
        if (!$this::EXTENSION_EDITABLE) abort(404);

        return [];
    }

    public function localizedFields()
    {
        if (!$this::EXTENSION_EDITABLE || !$this::EXTENSION_TRANSLATABLE) abort(404);

        return [];
    }

    public function save(array $data = [], array $localizedData = [])
    {
        if (!$this::EXTENSION_EDITABLE) abort(404);

        $constructing_data = array_merge($data, $localizedData);
        if (setOption($this->EXTENSION_OPTION(), $constructing_data, 'ext:' . $this->getName())) {
            return true;
        }

        return [trans('error.database_update')];
    }
}