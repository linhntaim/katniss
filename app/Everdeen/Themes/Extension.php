<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-11-16
 * Time: 11:46
 */

namespace Katniss\Everdeen\Themes;

use Katniss\Everdeen\Themes\HomeThemes\HomeThemeFacade;
use Katniss\Everdeen\Utils\AppOptionHelper;

abstract class Extension extends Plugin
{
    /**
     * @var array
     */
    private static $sharedData = [];

    /**
     * @return \stdClass|null
     */
    public static function getSharedData($extensionName = null)
    {
        if (empty($extensionName)) {
            $extensionName = self::NAME;
        }

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
        self::$sharedData[$this::NAME] = $data;
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
    protected $currentLocalizedData;

    public function __construct()
    {
        parent::__construct();

        if ($this::EDITABLE) {
            $this->fromDataConstruct((array)AppOptionHelper::get($this->getOptionName(), []));
            $this->__init();
        }
    }

    public function getOptionName()
    {
        return 'extension_' . $this::NAME;
    }

    public function getProperty($name, $locale = '')
    {
        if (!$this::EDITABLE) abort(404);

        return parent::getProperty($name, $locale);
    }

    public function viewAdmin()
    {
        if (!$this::EDITABLE) abort(404);

        return empty($this::THEME_NAME) ? HomeThemeFacade::commonAdminExtension($this::NAME) : HomeThemeFacade::adminExtension($this::NAME);
    }

    public function viewAdminParams()
    {
        if (!$this::EDITABLE) abort(404);

        return parent::viewAdminParams();
    }

    public function validationRules()
    {
        if (!$this::EDITABLE) abort(404);

        return parent::validationRules();
    }

    public function localizedValidationRules()
    {
        if (!$this::EDITABLE || !$this::TRANSLATABLE) abort(404);

        return parent::localizedValidationRules();
    }

    public function fields()
    {
        if (!$this::EDITABLE) abort(404);

        return parent::fields();
    }

    public function localizedFields()
    {
        if (!$this::EDITABLE || !$this::TRANSLATABLE) abort(404);

        return parent::localizedFields();
    }

    public function save(array $data = [], array $localizedData = [])
    {
        if (!$this::EDITABLE) abort(404);

        if (AppOptionHelper::set($this->getOptionName(), $this->toDataConstruct($data, $localizedData), 'ext:' . $this->getName())) {
            return true;
        }

        return [trans('error.database_update')];
    }
}