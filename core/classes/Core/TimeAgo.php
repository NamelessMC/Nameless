<?php

function timeAgoInWords(string $timestring, string $timezone = null): string {
    return (new TimeAgo($timezone))->inWords($timestring, 'now');
}

/**
 * This class can help you find out just how much time has passed between two dates.
 * It has two functions you can call:
 * - `inWords()` which gives you the "time ago in words" between two dates.
 * - `dateDifference()` which returns an array of years, months, days, hours, minutes and seconds between the two dates.
 *
 * @package NamelessMC\Core
 * @author jimmiw
 * @author Samerton
 * @version 2.0.0-pr8
 * @license MIT
 * @site http://github.com/jimmiw/php-time-ago
 */
class TimeAgo {

    // defines the number of seconds per "unit"
    private int $_secondsPerMinute = 60;
    private int $_secondsPerHour = 3600;
    private int $_secondsPerDay = 86400;
    private int $_secondsPerMonth = 2592000;
    private int $_secondsPerYear = 31104000;
    private string $_timezone;

    public function __construct(string $timezone = null) {
        // if the $timezone is null, we take 'Europe/London' as the default
        // this was done, because the parent construct tossed an exception
        if ($timezone == null) {
            $timezone = 'Europe/London';
        }

        $this->_timezone = $timezone;
    }

    public function inWords(string $past, Language $language, $now = 'now'): string {
        // sets the default timezone
        date_default_timezone_set($this->_timezone);
        // finds the past in datetime
        $past = strtotime($past);
        // finds the current datetime
        $now = strtotime($now);

        // finds the time difference
        $timeDifference = $now - $past;

        // less than 29secs
        if ($timeDifference <= 29) {
            $key = 'less_than_a_minute';
        } else {
            // more than 29secs and less than 1min29secss
            if ($timeDifference <= 89) {
                $key = '1_minute';
            } else {
                // between 1min30secs and 44mins29secs
                if ($timeDifference <= (($this->_secondsPerMinute * 44) + 29)) {
                    $replace = floor($timeDifference / $this->_secondsPerMinute);
                    $key = '_minutes';
                } else {
                    // between 44mins30secs and 1hour29mins29secs
                    if (
                        $timeDifference > (($this->_secondsPerMinute * 44) + 29)
                        &&
                        $timeDifference < (($this->_secondsPerMinute * 89) + 29)
                    ) {
                        $key = 'about_1_hour';
                    } else {
                        // between 1hour29mins30secs and 23hours59mins29secs
                        if (
                            $timeDifference > (
                                ($this->_secondsPerMinute * 89) +
                                29
                            )
                            &&
                            $timeDifference <= (
                                ($this->_secondsPerHour * 23) +
                                ($this->_secondsPerMinute * 59) +
                                29
                            )
                        ) {
                            $replace = floor($timeDifference / $this->_secondsPerHour);
                            if ($replace == 1) {
                                $key = 'about_1_hour';
                                unset($replace);
                            } else {
                                $key = '_hours';
                            }
                        } else {
                            // between 23hours59mins30secs and 47hours59mins29secs
                            if (
                                $timeDifference > (
                                    ($this->_secondsPerHour * 23) +
                                    ($this->_secondsPerMinute * 59) +
                                    29
                                )
                                &&
                                $timeDifference <= (
                                    ($this->_secondsPerHour * 47) +
                                    ($this->_secondsPerMinute * 59) +
                                    29
                                )
                            ) {
                                $key = '1_day';
                            } else {
                                // between 47hours59mins30secs and 29days23hours59mins29secs
                                if (
                                    $timeDifference > (
                                        ($this->_secondsPerHour * 47) +
                                        ($this->_secondsPerMinute * 59) +
                                        29
                                    )
                                    &&
                                    $timeDifference <= (
                                        ($this->_secondsPerDay * 29) +
                                        ($this->_secondsPerHour * 23) +
                                        ($this->_secondsPerMinute * 59) +
                                        29
                                    )
                                ) {
                                    $replace = floor($timeDifference / $this->_secondsPerDay);
                                    $key = '_days';
                                } else {
                                    // between 29days23hours59mins30secs and 59days23hours59mins29secs
                                    if (
                                        $timeDifference > (
                                            ($this->_secondsPerDay * 29) +
                                            ($this->_secondsPerHour * 23) +
                                            ($this->_secondsPerMinute * 59) +
                                            29
                                        )
                                        &&
                                        $timeDifference <= (
                                            ($this->_secondsPerDay * 59) +
                                            ($this->_secondsPerHour * 23) +
                                            ($this->_secondsPerMinute * 59) +
                                            29
                                        )
                                    ) {
                                        $key = 'about_1_month';
                                    } else {
                                        // between 59days23hours59mins30secs and 1year (minus 1sec)
                                        if (
                                            $timeDifference > (
                                                ($this->_secondsPerDay * 59) +
                                                ($this->_secondsPerHour * 23) +
                                                ($this->_secondsPerMinute * 59) +
                                                29
                                            )
                                            &&
                                            $timeDifference < $this->_secondsPerYear
                                        ) {
                                            $replace = round($timeDifference / $this->_secondsPerMonth);
                                            // if months is 1, then set it to 2, because we are "past" 1 month
                                            if ($replace == 1) {
                                                $replace = 2;
                                            }

                                            $key = '_months';
                                        } else {
                                            // between 1year and 2years (minus 1sec)
                                            if (
                                                $timeDifference >= $this->_secondsPerYear
                                                &&
                                                $timeDifference < ($this->_secondsPerYear * 2)
                                            ) {
                                                $key = 'about_1_year';
                                            } else {
                                                // 2years or more
                                                $replace = floor($timeDifference / $this->_secondsPerYear);
                                                $key = 'over_x_years';
                                            }
                                        }
                                    }
                                }
                            }
                        }
                    }
                }
            }
        }

        $term = $language->get('time', $key);

        if (count($exploded = explode('|', $term)) > 1) {
            if (function_exists('pluralForm')) {
                if (isset($replace)) {
                    return str_replace('{{count}}', $replace, pluralForm($replace, $exploded));
                }

                return 'Plural specified but replace not set for ' . Output::getClean($key);
            } else {
                return 'Plural form function not defined';
            }
        } else {
            if (isset($replace)) {
                return $language->get('time', $key, ['count' => $replace]);
            }

            return $term;
        }
    }

    public function dateDifference(string $past, string $now = 'now'): array {
        // initializes the placeholders for the different "times"
        $seconds = 0;
        $minutes = 0;
        $hours = 0;
        $days = 0;
        $months = 0;
        $years = 0;

        // sets the default timezone
        date_default_timezone_set($this->_timezone);

        // finds the past in datetime
        $past = strtotime($past);
        // finds the current datetime
        $now = strtotime($now);

        // calculates the difference
        $timeDifference = $now - $past;

        // starts determining the time difference
        if ($timeDifference >= 0) {
            switch ($timeDifference) {
                // finds the number of years
                case ($timeDifference >= $this->_secondsPerYear):
                    // uses floor to remove decimals
                    $years = floor($timeDifference / $this->_secondsPerYear);
                    // saves the amount of seconds left
                    $timeDifference -= ($years * $this->_secondsPerYear);
                    break;

                // finds the number of months
                case ($timeDifference >= $this->_secondsPerMonth && $timeDifference <= ($this->_secondsPerYear - 1)):
                    // uses floor to remove decimals
                    $months = floor($timeDifference / $this->_secondsPerMonth);
                    // saves the amount of seconds left
                    $timeDifference -= ($months * $this->_secondsPerMonth);
                    break;

                // finds the number of days
                case ($timeDifference >= $this->_secondsPerDay && $timeDifference <= ($this->_secondsPerYear - 1)):
                    // uses floor to remove decimals
                    $days = floor($timeDifference / $this->_secondsPerDay);
                    // saves the amount of seconds left
                    $timeDifference -= ($days * $this->_secondsPerDay);
                    break;

                // finds the number of hours
                case ($timeDifference >= $this->_secondsPerHour && $timeDifference <= ($this->_secondsPerDay - 1)):
                    // uses floor to remove decimals
                    $hours = floor($timeDifference / $this->_secondsPerHour);
                    // saves the amount of seconds left
                    $timeDifference -= ($hours * $this->_secondsPerHour);
                    break;

                // finds the number of minutes
                case ($timeDifference >= $this->_secondsPerMinute && $timeDifference <= ($this->_secondsPerHour - 1)):
                    // uses floor to remove decimals
                    $minutes = floor($timeDifference / $this->_secondsPerMinute);
                    // saves the amount of seconds left
                    $timeDifference -= ($minutes * $this->_secondsPerMinute);
                    break;

                // finds the number of seconds
                case ($timeDifference <= ($this->_secondsPerMinute - 1)):
                    // seconds is just what there is in the timeDifference variable
                    $seconds = $timeDifference;
            }
        }

        return [
            'years' => $years,
            'months' => $months,
            'days' => $days,
            'hours' => $hours,
            'minutes' => $minutes,
            'seconds' => $seconds
        ];
    }
}
