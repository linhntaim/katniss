<?php
/**
 * Created by PhpStorm.
 * User: Nguyen Tuan Linh
 * Date: 2015-09-24
 * Time: 07:22
 */

namespace Katniss\Everdeen\Utils;


use Carbon\Carbon;
use Katniss\Everdeen\Exceptions\KatnissException;
use Katniss\Everdeen\Models\User;

class DateTimeHelper
{
    const LONG_DATE_FUNCTION = 'longDate';
    const SHORT_DATE_FUNCTION = 'shortDate';
    const LONG_TIME_FUNCTION = 'longTime';
    const SHORT_TIME_FUNCTION = 'shortTime';

    const DATABASE_FORMAT = 'Y-m-d H:i:s';
    const WEEKS_PER_YEAR = 52;

    const DAY_TYPE_NONE = 0;
    const DAY_TYPE_START = -1;
    const DAY_TYPE_END = 1;
    const DAY_TYPE_NEXT_START = 2;

    private static $instance;

    private static $now;

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

    public static function syncNow($reset = false)
    {
        if ($reset) {
            self::$now = new \DateTime();
        }
        return self::$now->format(self::DATABASE_FORMAT);
    }

    public static function syncNowObject($reset = false)
    {
        if ($reset) {
            self::$now = new \DateTime();
        }
        return self::$now;
    }

    /**
     * @param integer|User $user
     * @return DateTimeHelper
     */
    public static function fromUser($user)
    {
        $settings = new Settings();
        $settings->fromUser($user);
        return new DateTimeHelper($settings);
    }

    private $transLongDate;
    private $transShortDate;
    private $transShortMonth;
    private $transLongTime;
    private $transShortTime;

    /**
     * Seconds
     *
     * @var float|int
     */
    private $dateTimeOffset;

    public function __construct($settings = null)
    {
        if ($settings == null) {
            $settings = settings();
        }

        $this->transLongDate = 'datetime.long_date_' . $settings->getLongDateFormat();
        $this->transShortDate = 'datetime.short_date_' . $settings->getShortDateFormat();
        $this->transShortMonth = 'datetime.short_month_' . $settings->getShortDateFormat();
        $this->transLongTime = 'datetime.long_time_' . $settings->getLongTimeFormat();
        $this->transShortTime = 'datetime.short_time_' . $settings->getShortTimeFormat();

        $this->dateTimeOffset = self::parseDateTimeOffsetByTimezone($settings->getTimezone());
    }

    public function setTransLongDate($value)
    {
        $this->transLongDate = 'datetime.long_date_' . $value;
    }

    public function setTransShortDate($value)
    {
        $this->transShortDate = 'datetime.short_date_' . $value;
    }

    public function setTransLongTime($value)
    {
        $this->transLongTime = 'datetime.long_time_' . $value;
    }

    public function setTransShortTime($value)
    {
        $this->transShortTime = 'datetime.short_time_' . $value;
    }

    /**
     * @return int
     */
    public function getDateTimeOffset()
    {
        return $this->dateTimeOffset;
    }

    public function getTimeZoneOffset()
    {
        $offset = round($this->getDateTimeOffset() / 3600, 2);
        $offset = $offset >= 0 ? '+' . $offset : '' . $offset;
        return 'UTC' . str_replace(['.25', '.50', '.75'], [':15', ':30', ':45'], $offset);
    }

    #region From Local Time to UTC
    public function from(\DateTime $time, $noOffset = false, &$diffDay = 0, $start = self::DAY_TYPE_NONE)
    {
        if (!$noOffset) {
            $offset = $this->getDateTimeOffset();
            if ($offset > 0) {
                $day = $time->format('d');
                $time->sub(new \DateInterval('PT' . $offset . 'S'));
                if ($time->format('d') != $day) $diffDay = -1;
            } elseif ($offset < 0) {
                $day = $time->format('d');
                $time->add(new \DateInterval('PT' . abs($offset) . 'S'));
                if ($time->format('d') != $day) $diffDay = 1;
            }
        }
        return self::applyStartType($time, $start);
    }

    public function fromFormat($format, $inputString, $noOffset = false, &$diffDay = 0, $start = self::DAY_TYPE_NONE)
    {
        return $this->from(\DateTime::createFromFormat($format, $inputString, new \DateTimeZone('UTC')), $noOffset, $diffDay, $start);
    }

    public function fromFormatToFormat($currentFormat, $inputString, $toFormat = null, $noOffset = false, &$diffDay = 0, $start = self::DAY_TYPE_NONE)
    {
        if (empty($toFormat)) $toFormat = $currentFormat;
        $now = $this->fromFormat($currentFormat, $inputString, $noOffset, $diffDay, $start);
        return $now !== false ? $now->format($toFormat) : false;
    }

    public function fromFormatToDatabaseFormat($currentFormat, $inputString, $noOffset = false, &$diffDay = 0, $start = self::DAY_TYPE_NONE)
    {
        return $this->fromFormatToFormat($currentFormat, $inputString, self::DATABASE_FORMAT, $noOffset, $diffDay, $start);
    }

    public function fromToFormat(\DateTime $time, $toFormat, $noOffset = false, &$diffDay = 0, $start = self::DAY_TYPE_NONE)
    {
        return $this->from($time, $noOffset, $diffDay, $start)
            ->format($toFormat);
    }

    public function fromToDatabaseFormat(\DateTime $time, $noOffset = false, &$diffDay = 0, $start = self::DAY_TYPE_NONE)
    {
        return $this->from($time, $noOffset, $diffDay, $start)
            ->format(self::DATABASE_FORMAT);
    }

    public function convertToUTC(\DateTime $time)
    {
        $offset = $this->getDateTimeOffset();
        if ($offset > 0) {
            $time->sub(new \DateInterval('PT' . $offset . 'S'));
        } elseif ($offset < 0) {
            $time->add(new \DateInterval('PT' . abs($offset) . 'S'));
        }
        return $time;
    }
    #endregion

    #region From UTC to Local Time
    public function getObject($time = 'now', $noOffset = false, &$diffDay = 0, $start = self::DAY_TYPE_NONE)
    {
        $now = $time instanceof \DateTime ? $time : new \DateTime($time, new \DateTimeZone('UTC'));
        if (!$noOffset) {
            $offset = $this->getDateTimeOffset();
            if ($offset > 0) {
                $day = $now->format('d');
                $now->add(new \DateInterval('PT' . $offset . 'S'));
                if ($now->format('d') != $day) $diffDay = 1;
            } elseif ($offset < 0) {
                $day = $now->format('d');
                $now->sub(new \DateInterval('PT' . abs($offset) . 'S'));
                if ($now->format('d') != $day) $diffDay = -1;
            }
        }
        return self::applyStartType($now, $start);
    }

    public function getBags($time = 'now', $noOffset = false, &$diffDay = 0, $start = self::DAY_TYPE_NONE)
    {
        $now = $this->getObject($time, $noOffset, $diffDay, $start);
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
            'lw' => self::transPhpDayOfWeek($now->format('w')),
            'sw' => self::transShortPhpDayOfWeek($now->format('w')),
        ];
    }

    public function compound($func_1 = self::SHORT_DATE_FUNCTION, $separation = ' ', $func_2 = self::SHORT_TIME_FUNCTION, $time = 'now', $no_offset = false)
    {
        $allowedFunctions = [self::LONG_DATE_FUNCTION, self::LONG_TIME_FUNCTION, self::SHORT_DATE_FUNCTION, self::SHORT_TIME_FUNCTION];
        if (!in_array($func_1, $allowedFunctions) || !in_array($func_2, $allowedFunctions)) {
            throw new KatnissException('Not allowed methods');
        }

        return call_user_func(array($this, $func_1), $time, $no_offset)
            . $separation
            . call_user_func(array($this, $func_2), $time, $no_offset);
    }

    public function compoundBags($func_1 = self::SHORT_DATE_FUNCTION, $separation = ' ', $func_2 = self::SHORT_TIME_FUNCTION, array $bags = [])
    {
        $allowedFunctions = [self::LONG_DATE_FUNCTION, self::LONG_TIME_FUNCTION, self::SHORT_DATE_FUNCTION, self::SHORT_TIME_FUNCTION];
        if (!in_array($func_1, $allowedFunctions) || !in_array($func_2, $allowedFunctions)) {
            throw new KatnissException('Not allowed methods');
        }
        $func_1 .= 'FromBags';
        $func_2 .= 'FromBags';
        return call_user_func(array($this, $func_1), $bags)
            . $separation
            . call_user_func(array($this, $func_2), $bags);
    }

    public function longDayOfWeek($time = 'now', $no_offset = false)
    {
        return $this->getBags($time, $no_offset)['lw'];
    }

    public function shortDayOfWeek($time = 'now', $no_offset = false)
    {
        return $this->getBags($time, $no_offset)['sw'];
    }

    public function longDate($time = 'now', $no_offset = false)
    {
        return $this->longDateFromBags($this->getBags($time, $no_offset));
    }

    public function shortDate($time = 'now', $no_offset = false)
    {
        return $this->shortDateFromBags($this->getBags($time, $no_offset));
    }

    public function shortMonth($time = 'now', $no_offset = false)
    {
        return $this->shortMonthFromBags($this->getBags($time, $no_offset));
    }

    public function longTime($time = 'now', $no_offset = false)
    {
        return $this->longTimeFromBags($this->getBags($time, $no_offset));
    }

    public function shortTime($time = 'now', $no_offset = false)
    {
        return $this->shortTimeFromBags($this->getBags($time, $no_offset));
    }

    public function longDateFromBags(array $bags)
    {
        return trans($this->transLongDate, $bags);
    }

    public function shortDateFromBags(array $bags)
    {
        return trans($this->transShortDate, $bags);
    }

    public function shortMonthFromBags(array $bags)
    {
        return trans($this->transShortMonth, $bags);
    }

    public function longTimeFromBags(array $bags)
    {
        return trans($this->transLongTime, $bags);
    }

    public function shortTimeFromBags(array $bags)
    {
        return trans($this->transShortTime, $bags);
    }

    public function format($format, $time = 'now', $noOffset = false, &$diffDay = 0, $start = self::DAY_TYPE_NONE)
    {
        return $this->getObject($time, $noOffset, $diffDay, $start)
            ->format($format);
    }

    public function sampleTimeFromSchedule($schedule, $noOffset = false)
    {
        $times = explode(':', $schedule->time_from);
        $from = (new Carbon())->modify('this week')->modify('this ' . DateTimeHelper::transDayOfWeek($schedule->day_of_week_from, 'en'))
            ->setTime($times[0], $times[1], $times[2]);
        $times = explode(':', $schedule->time_to);
        $to = (new Carbon())->modify('this week')->modify('this ' . DateTimeHelper::transDayOfWeek($schedule->day_of_week_to, 'en'))
            ->setTime($times[0], $times[1], $times[2]);
        if ($to->lt($from)) {
            $to->addDays(7);
        }
        if (!$noOffset) {
            if ($this->dateTimeOffset > 0) {
                $from->addSeconds($this->dateTimeOffset);
                $to->addSeconds($this->dateTimeOffset);
            } else {
                $from->subSeconds($this->dateTimeOffset);
                $to->subSeconds($this->dateTimeOffset);
            }
        }
        return [
            'from' => $from,
            'to' => $to,
            'duration_in_minutes' => abs($from->diffInMinutes($to)), // minutes
        ];
    }
    #endregion

    #region Static Methods
    public static function parseDateTimeOffsetByTimezone($timeZone)
    {
        if (empty($timeZone)) {
            return 0;
        }
        if ($timeZone != 'UTC' && strpos($timeZone, 'UTC') === 0) {
            return floatval(str_replace('UTC', '', $timeZone)) * 3600;
        }
        $currentTimeZone = new \DateTimeZone($timeZone);
        return $currentTimeZone->getOffset(new \DateTime('now', new \DateTimeZone('UTC')));
    }

    public static function applyStartType(\DateTime $time, $start = self::DAY_TYPE_NONE)
    {
        if ($start == self::DAY_TYPE_NEXT_START) {
            $time->setTime(0, 0, 0)->add(new \DateInterval('P1D'));
        } elseif ($start == self::DAY_TYPE_END) {
            $time->setTime(23, 59, 59);
        } elseif ($start == self::DAY_TYPE_START) {
            $time->setTime(0, 0, 0);
        }
        return $time;
    }

    public static function dayOfWeek($phpDayOfWeek)
    {
        return $phpDayOfWeek == 0 ? 6 : ($phpDayOfWeek - 1);
    }

    public static function phpDayOfWeek($dayOfWeek)
    {
        return $dayOfWeek == 6 ? 0 : ($dayOfWeek + 1);
    }

    public static function transDayOfWeek($dayOfWeek, $locale = null)
    {
        return trans('datetime.day_' . $dayOfWeek, [], $locale);
    }

    public static function transPhpDayOfWeek($phpDayOfWeek, $locale = null)
    {
        return trans('datetime.day_' . self::dayOfWeek($phpDayOfWeek), [], $locale);
    }

    public static function transShortDayOfWeek($dayOfWeek, $locale = null)
    {
        return trans('datetime.short_day_' . $dayOfWeek, [], $locale);
    }

    public static function transShortPhpDayOfWeek($phpDayOfWeek, $locale = null)
    {
        return trans('datetime.short_day_' . self::dayOfWeek($phpDayOfWeek), [], $locale);
    }

    /**
     * Get list timezone
     * @return array
     */
    public static function getTimezoneList()
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
        $structure['UTC']['UTC'] = 'UTC';
        // UTC offset
        $structure[trans('datetime.utc_offsets')] = [];
        $offset_range = array(-12, -11.5, -11, -10.5, -10, -9.5, -9, -8.5, -8, -7.5, -7, -6.5, -6, -5.5, -5, -4.5, -4, -3.5, -3, -2.5, -2, -1.5, -1, -0.5,
            0, 0.5, 1, 1.5, 2, 2.5, 3, 3.5, 4, 4.5, 5, 5.5, 5.75, 6, 6.5, 7, 7.5, 8, 8.5, 8.75, 9, 9.5, 10, 10.5, 11, 11.5, 12, 12.75, 13, 13.75, 14);

        foreach ($offset_range as $offset) {
            $offset_value = 0 <= $offset ? '+' . $offset : (string)$offset;
            $offset_name = str_replace(array('.25', '.5', '.75'), array(':15', ':30', ':45'), $offset_value);
            $offset_name = 'UTC' . $offset_name;
            $offset_value = 'UTC' . $offset_value;
            $structure[trans('datetime.utc_offsets')][$offset_value] = $offset_name;
        }

        // PHP Timezones
        foreach ($zones as $continent => $cities) {
            $structure[$continent] = [];

            foreach ($cities as $zone => $city) {
                $structure[$continent][$zone] = $city;
            }
        }

        return $structure;
    }

    public static function getTimezoneValues()
    {
        $timezoneValues = [];
        $timezoneValues[] = 'UTC';
        $offset_range = [-12, -11.5, -11, -10.5, -10, -9.5, -9, -8.5, -8, -7.5, -7, -6.5, -6, -5.5, -5, -4.5, -4, -3.5, -3, -2.5, -2, -1.5, -1, -0.5,
            0, 0.5, 1, 1.5, 2, 2.5, 3, 3.5, 4, 4.5, 5, 5.5, 5.75, 6, 6.5, 7, 7.5, 8, 8.5, 8.75, 9, 9.5, 10, 10.5, 11, 11.5, 12, 12.75, 13, 13.75, 14];
        foreach ($offset_range as $offset) {
            $timezoneValues[] = 'UTC' . (0 <= $offset ? '+' . $offset : (string)$offset);
        }
        foreach (\DateTimeZone::listIdentifiers() as $zone) {
            $timezoneValues[] = $zone;
        }
        return $timezoneValues;
    }

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

    protected static function getExampleBags()
    {
        return self::getInstance()->getBags(date('Y') . '-12-24 08:00:00', true);
    }

    /**
     * @param int $selected_format
     * @return string
     */
    public static function getLongDateFormatsAsOptions($selected_format = 0)
    {
        $options = '';
        for ($i = 0; $i < 4; ++$i) {
            $options .= '<option value="' . $i . '"' . ($selected_format == $i ? ' selected' : '') . '>' . trans('datetime.long_date_' . $i, self::getExampleBags()) . '</option>';
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
            $options .= '<option value="' . $i . '"' . ($selected_format == $i ? ' selected' : '') . '>' . trans('datetime.short_date_' . $i, self::getExampleBags()) . '</option>';
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
            $options .= '<option value="' . $i . '"' . ($selected_format == $i ? ' selected' : '') . '>' . trans('datetime.long_time_' . $i, self::getExampleBags()) . '</option>';
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
            $options .= '<option value="' . $i . '"' . ($selected_format == $i ? ' selected' : '') . '>' . trans('datetime.short_time_' . $i, self::getExampleBags()) . '</option>';
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

    public static function getAndroidFormatBags()
    {
        return [
            'ld' => 'EEEE',
            'sd' => 'EEE',
            '1d' => 'd',
            '2d' => 'dd',
            'sm' => 'MMM',
            'lm' => 'MMMM',
            '1m' => 'M',
            '2m' => 'MM',
            '2y' => 'yy',
            '4y' => 'yyyy',
            '1h' => 'h',
            '1hf' => 'hh',
            '2h' => 'HH',
            '2i' => 'mm',
            '2s' => 'ss',
            'ut' => 'a',
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
        return self::getInstance()->longDateFromBags(self::getFormatBags());
    }

    public static function longDateJsFormat()
    {
        return self::getInstance()->longDateFromBags(self::getMomentJsFormatBags());
    }

    public static function longDatePickerJsFormat()
    {
        return self::getInstance()->longDateFromBags(self::getDatePickerJsFormatBags());
    }

    public static function shortDateFormat()
    {
        return self::getInstance()->shortDateFromBags(self::getFormatBags());
    }

    public static function shortMonthFormat()
    {
        return self::getInstance()->shortMonthFromBags(self::getFormatBags());
    }

    public static function shortDateJsFormat()
    {
        return self::getInstance()->shortDateFromBags(self::getMomentJsFormatBags());
    }

    public static function shortDatePickerJsFormat()
    {
        return self::getInstance()->shortDateFromBags(self::getDatePickerJsFormatBags());
    }

    public static function shortMonthPickerJsFormat()
    {
        return self::getInstance()->shortMonthFromBags(self::getDatePickerJsFormatBags());
    }

    public static function longTimeFormat()
    {
        return self::getInstance()->longTimeFromBags(self::getFormatBags());
    }

    public static function longTimeJsFormat()
    {
        return self::getInstance()->longTimeFromBags(self::getMomentJsFormatBags());
    }

    public static function shortTimeFormat()
    {
        return self::getInstance()->shortTimeFromBags(self::getFormatBags());
    }

    public static function shortTimeJsFormat()
    {
        return self::getInstance()->shortTimeFromBags(self::getMomentJsFormatBags());
    }

    public static function shortDateAndroidFormat()
    {
        return self::getInstance()->shortDateFromBags(self::getAndroidFormatBags());
    }

    public static function longDateAndroidFormat()
    {
        return self::getInstance()->longDateFromBags(self::getAndroidFormatBags());
    }

    public static function shortTimeAndroidFormat()
    {
        return self::getInstance()->shortTimeFromBags(self::getAndroidFormatBags());
    }

    public static function longTimeAndroidFormat()
    {
        return self::getInstance()->longTimeFromBags(self::getAndroidFormatBags());
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

    public static function diffMinute($time_from, $time_to = 'now')
    {
        return self::diff($time_from, $time_to)->i;
    }

    public static function diffInRealWeeks($timeFrom, $timeTo)
    {
        return Carbon::parse($timeTo)->modify('this week')->modify('this Monday')
            ->diffInWeeks(Carbon::parse($timeFrom)->modify('this week')->modify('this Monday'));
    }

    public static function full($time = 'now')
    {
        return date(self::DATABASE_FORMAT, strtotime($time));
    }

    public static function fullWithTimeOffset($time = 'now', $offset = 0)
    {
        $time = new \DateTime($time);
        return $time->modify($offset . ' hours')->format(self::DATABASE_FORMAT);
    }

    public static function diffWeek($time_from, $time_to = 'now')
    {
        $time_from = new \DateTime($time_from, new \DateTimeZone('UTC'));
        $time_to = new \DateTime($time_to, new \DateTimeZone('UTC'));
        $diff = (($time_to->format('W') - $time_from->format('W')) >= 0) ? ($time_to->format('W') - $time_from->format('W')) : ($time_to->format('W') - $time_from->format('W') + self::WEEKS_PER_YEAR);
        return $diff;
    }
    #endregion
}