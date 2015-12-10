<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-09-24
 * Time: 07:22
 */

namespace Katniss\Models\Helpers;


class DateTimeHelper
{
    private static $instance;

    /**
     * @return DateTimeHelper
     */
    public static function getInstance()
    {
        if (empty(self::$instance)) {
            self::$instance = new DateTimeHelper();
        }

        return self::$instance;
    }

    /**
     * @var float|int
     */
    private $dateTimeOffset;

    private function __construct()
    {
        $timeZone = session('settings.timezone');
        if (empty($timeZone)) {
            $this->dateTimeOffset = 0;
            return;
        }
        if ($timeZone != 'UTC' && strpos($timeZone, 'UTC') === 0) {
            $this->dateTimeOffset = floatval(str_replace('UTC', '', $timeZone)) * 3600;
        } else {
            $currentTimeZone = new \DateTimeZone($timeZone);
            $this->dateTimeOffset = $currentTimeZone->getOffset(new \DateTime('now', new \DateTimeZone('UTC')));
        }
    }

    /**
     * @return float|int
     */
    public function getDateTimeOffset()
    {
        return $this->dateTimeOffset;
    }

    /**
     * @param $format
     * @param $inputString
     * @return \DateTime|bool
     */
    public function fromFormat($format, $inputString, $no_offset = false)
    {
        $now = \DateTime::createFromFormat($format, $inputString);
        if ($now === false) return false;
        if ($no_offset) {
            $offset = $this->getDateTimeOffset();
            if ($offset > 0) {
                $now->sub(new \DateInterval('PT' . $offset . 'S'));
            } elseif ($offset < 0) {
                $now->add(new \DateInterval('PT' . abs($offset) . 'S'));
            }
        }
        return $now;
    }

    public function convertToDatabaseFormat($current_format, $inputString, $no_offset = false)
    {
        $now = $this->fromFormat($current_format, $inputString, $no_offset);
        return $now !== false ? $now->format('Y-m-d H:i:s') : false;
    }

    /**
     * @param string $format
     * @param string $time
     * @return string
     */
    public function format($format, $time = 'now', $start = 0, $no_offset = false)
    {
        $now = $time instanceof \DateTime ? $time : new \DateTime($time, new \DateTimeZone('UTC'));
        if ($no_offset) {
            $offset = $this->getDateTimeOffset();
            if ($offset > 0) {
                $now->add(new \DateInterval('PT' . $offset . 'S'));
            } elseif ($offset < 0) {
                $now->sub(new \DateInterval('PT' . abs($offset) . 'S'));
            }
        }
        if ($start == 1) {
            $now->setTime(23, 59, 0);
        } elseif ($start == -1) {
            $now->setTime(0, 0);
        }
        return $now->format($format);
    }

    /**
     * @param string $time
     * @return array
     */
    public function getBags($time = 'now')
    {
        $now = $time instanceof \DateTime ? $time : new \DateTime($time, new \DateTimeZone('UTC'));
        $offset = $this->getDateTimeOffset();
        if ($offset > 0) {
            $now->add(new \DateInterval('PT' . $offset . 'S'));
        } elseif ($offset < 0) {
            $now->sub(new \DateInterval('PT' . abs($offset) . 'S'));
        }
        return [
            'ld' => $now->format('l'),
            'sd' => $now->format('D'),
            '1d' => $now->format('j'),
            '2d' => $now->format('d'),
            'sm' => $now->format('M'),
            'lm' => $now->format('F'),
            '2m' => $now->format('m'),
            '2y' => $now->format('y'),
            '4y' => $now->format('Y'),
            '1h' => $now->format('g'),
            '1hf' => $now->format('h'),
            '2h' => $now->format('H'),
            '2i' => $now->format('i'),
            '2s' => $now->format('s'),
            'ut' => $now->format('A'),
            'lt' => $now->format('a'),
        ];
    }

    public function compound($func_1, $separation, $func_2, $time = 'now')
    {
        return call_user_func(array($this, $func_1), $time)
        . $separation
        . call_user_func(array($this, $func_2), $time);
    }

    public function longDate($time = 'now')
    {
        return trans('datetime.long_date_' . session('settings.long_date_format'), $this->getBags($time));
    }

    public function shortDate($time = 'now')
    {
        return trans('datetime.short_date_' . session('settings.short_date_format'), $this->getBags($time));
    }

    public function longTime($time = 'now')
    {
        return trans('datetime.long_time_' . session('settings.long_time_format'), $this->getBags($time));
    }

    public function shortTime($time = 'now')
    {
        return trans('datetime.short_time_' . session('settings.short_time_format'), $this->getBags($time));
    }

    public function getCurrentTimeZone()
    {
        $offset = round($this->getDateTimeOffset() / 3600, 2);
        $offset = $offset >= 0 ? '+' . $offset : '' . $offset;

        return 'UTC' . str_replace(array('.25', '.50', '.75'), array(':15', ':30', ':45'), $offset);
    }

    #region Static Methods
    /**
     * @param string $selected_zone
     * @return string
     */
    public static function getTimeZoneListAsOptions($selected_zone = '')
    {
        $zones = array(
            'Africa' => [],
            'America' => [],
            'Antarctica' => [],
            'Arctic' => [],
            'Asia' => [],
            'Atlantic' => [],
            'Australia' => [],
            'Europe' => [],
            'Indian' => [],
            'Pacific' => []
        );
        foreach (\DateTimeZone::listIdentifiers() as $zone) {
            $zonePart = explode('/', $zone);
            $continent = $zonePart[0];
            $city = isset($zonePart[1]) ? $zonePart[1] : '';
            $subCity = isset($zonePart[2]) ? $zonePart[2] : '';

            if (!isset($zones[$continent])) {
                continue;
            }

            $zones[$continent][$zone] = str_replace('_', ' ', $city) . (empty($subCity) ? '' : ' - ' . str_replace('_', ' ', $subCity));
        }

        $structure = array();

        // UTC
        $structure[] = '<optgroup label="UTC">';
        $structure[] = '<option value="UTC"' . ($selected_zone == 'UTC' ? ' selected' : '') . '>UTC</option>';
        $structure[] = '</optgroup>';

        // UTC offsets
        $structure[] = '<optgroup label="' . trans('datetime.utc_offsets') . '">';
        $offset_range = array(-12, -11.5, -11, -10.5, -10, -9.5, -9, -8.5, -8, -7.5, -7, -6.5, -6, -5.5, -5, -4.5, -4, -3.5, -3, -2.5, -2, -1.5, -1, -0.5,
            0, 0.5, 1, 1.5, 2, 2.5, 3, 3.5, 4, 4.5, 5, 5.5, 5.75, 6, 6.5, 7, 7.5, 8, 8.5, 8.75, 9, 9.5, 10, 10.5, 11, 11.5, 12, 12.75, 13, 13.75, 14);
        foreach ($offset_range as $offset) {
            $offset_value = 0 <= $offset ? '+' . $offset : (string)$offset;
            $offset_name = str_replace(array('.25', '.5', '.75'), array(':15', ':30', ':45'), $offset_value);
            $offset_name = 'UTC' . $offset_name;
            $offset_value = 'UTC' . $offset_value;
            $structure[] = '<option value="' . $offset_value . '"' . ($offset_value === $selected_zone ? ' selected' : '') . '>' . $offset_name . "</option>";
        }
        $structure[] = '</optgroup>';

        // PHP Timezones
        foreach ($zones as $continent => $cities) {
            $structure[] = '<optgroup label="' . $continent . '">';
            foreach ($cities as $zone => $city) {
                $structure[] = '<option value="' . $zone . '"' . ($selected_zone == $zone ? ' selected' : '') . '>' . $city . '</option>';
            }
            $structure[] = '</optgroup>';
        }

        return join("\n", $structure);
    }

    /**
     * @param int $selected_day
     * @return string
     */
    public static function getDaysOfWeekAsOptions($selected_day = 0)
    {
        $options = '';
        for ($i = 0; $i < 7; ++$i) {
            $options .= '<option value="' . $i . '"' . ($selected_day == $i ? ' selected' : '') . '>' . trans('datetime.day_' . $i) . '</option>';
        }
        return $options;
    }

    /**
     * @param int $selected_format
     * @return string
     */
    public static function getLongDateFormatsAsOptions($selected_format = 0)
    {
        $options = '';
        for ($i = 0; $i < 4; ++$i) {
            $options .= '<option value="' . $i . '"' . ($selected_format == $i ? ' selected' : '') . '>' . trans('datetime.long_date_' . $i, self::getInstance()->getBags()) . '</option>';
        }
        return $options;
    }

    /**
     * @param int $selected_format
     * @return string
     */
    public static function getShortDateFormatsAsOptions($selected_format = 0)
    {
        $options = '';
        for ($i = 0; $i < 4; ++$i) {
            $options .= '<option value="' . $i . '"' . ($selected_format == $i ? ' selected' : '') . '>' . trans('datetime.short_date_' . $i, self::getInstance()->getBags()) . '</option>';
        }
        return $options;
    }

    /**
     * @param int $selected_format
     * @return string
     */
    public static function getLongTimeFormatsAsOptions($selected_format = 0)
    {
        $options = '';
        for ($i = 0; $i < 5; ++$i) {
            $options .= '<option value="' . $i . '"' . ($selected_format == $i ? ' selected' : '') . '>' . trans('datetime.long_time_' . $i, self::getInstance()->getBags()) . '</option>';
        }
        return $options;
    }

    /**
     * @param int $selected_format
     * @return string
     */
    public static function getShortTimeFormatsAsOptions($selected_format = 0)
    {
        $options = '';
        for ($i = 0; $i < 5; ++$i) {
            $options .= '<option value="' . $i . '"' . ($selected_format == $i ? ' selected' : '') . '>' . trans('datetime.short_time_' . $i, self::getInstance()->getBags()) . '</option>';
        }
        return $options;
    }

    public static function getFormatBags()
    {
        return [
            'ld' => 'l',
            'sd' => 'D',
            '1d' => 'j',
            '2d' => 'd',
            'sm' => 'M',
            'lm' => 'F',
            '1m' => 'n',
            '2m' => 'm',
            '2y' => 'y',
            '4y' => 'Y',
            '1h' => 'g',
            '1hf' => 'h',
            '2h' => 'H',
            '2i' => 'i',
            '2s' => 's',
            'ut' => 'A',
            'lt' => 'a',
        ];
    }

    public static function getDatePickerJsFormatBags()
    {
        return [
            'ld' => 'DD',
            'sd' => 'D',
            '1d' => 'd',
            '2d' => 'dd',
            'sm' => 'M',
            'lm' => 'MM',
            '1m' => 'm',
            '2m' => 'mm',
            '2y' => 'yy',
            '4y' => 'yyyy'
        ];
    }

    public static function getMomentJsFormatBags()
    {
        return [
            'ld' => 'dddd',
            'sd' => 'ddd',
            '1d' => 'D',
            '2d' => 'DD',
            'sm' => 'MMM',
            'lm' => 'MMMM',
            '1m' => 'M',
            '2m' => 'MM',
            '2y' => 'YY',
            '4y' => 'YYYY',
            '1h' => 'h',
            '1hf' => 'hh',
            '2h' => 'HH',
            '2i' => 'mm',
            '2s' => 'ss',
            'ut' => 'A',
            'lt' => 'a',
        ];
    }

    public static function compoundFormat($func_1, $separation, $func_2)
    {
        return call_user_func(array(self::class, $func_1 . 'Format'))
        . $separation
        . call_user_func(array(self::class, $func_2 . 'Format'));
    }

    public static function compoundJsFormat($func_1, $separation, $func_2)
    {
        return call_user_func(array(self::class, $func_1 . 'JsFormat'))
        . $separation
        . call_user_func(array(self::class, $func_2 . 'JsFormat'));
    }

    public static function longDateFormat()
    {
        return trans('datetime.long_date_' . session('settings.long_date_format'), self::getFormatBags());
    }

    public static function longDateJsFormat()
    {
        return trans('datetime.long_date_' . session('settings.long_date_format'), self::getMomentJsFormatBags());
    }

    public static function shortDateFormat()
    {
        return trans('datetime.short_date_' . session('settings.short_date_format'), self::getFormatBags());
    }

    public static function shortDateJsFormat()
    {
        return trans('datetime.short_date_' . session('settings.short_date_format'), self::getMomentJsFormatBags());
    }

    public static function shortDatePickerJsFormat()
    {
        return trans('datetime.short_date_' . session('settings.short_date_format'), self::getDatePickerJsFormatBags());
    }

    public static function longTimeFormat()
    {
        return trans('datetime.long_time_' . session('settings.long_time_format'), self::getFormatBags());
    }

    public static function longTimeJsFormat()
    {
        return trans('datetime.long_time_' . session('settings.long_time_format'), self::getMomentJsFormatBags());
    }

    public static function shortTimeFormat()
    {
        return trans('datetime.short_time_' . session('settings.short_time_format'), self::getFormatBags());
    }

    public static function shortTimeJsFormat()
    {
        return trans('datetime.short_time_' . session('settings.short_time_format'), self::getMomentJsFormatBags());
    }

    public static function diff($time_from, $time_to = 'now')
    {
        $time_from = new \DateTime($time_from, new \DateTimeZone('UTC'));
        $time_to = new \DateTime($time_to, new \DateTimeZone('UTC'));
        $diff = $time_to->diff($time_from);
        return $diff;
    }

    public static function diffYear($time_from, $time_to = 'now')
    {
        return self::diff($time_from, $time_to)->y;
    }

    public static function diffDay($time_from, $time_to = 'now')
    {
        return self::diff($time_from, $time_to)->d;
    }

    public static function diffMonth($time_from, $time_to = 'now')
    {
        return self::diff($time_from, $time_to)->m;
    }
    #endregion
}