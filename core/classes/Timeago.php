<?php
/*
MIT LICENSE
Copyright © 2014 Jimmi Westerberg (http://westsworld.dk)

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the “Software”), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED “AS IS”, WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.
*/

/*
 *  Modified by Samerton for NamelessMC (https://github.com/NamelessMC/Nameless)
 */


function timeAgoInWords($timestring, $timezone = null) {
    $timeAgo = new TimeAgo($timezone);

    return $timeAgo->inWords($timestring, "now");
}

/**
 * This class can help you find out just how much time has passed between
 * two dates.
 *
 * It has two functions you can call:
 * inWords() which gives you the "time ago in words" between two dates.
 * dateDifference() which returns an array of years,months,days,hours,minutes and
 * seconds between the two dates.
 *
 * @author jimmiw
 * @since 0.2.0 (2010/05/05)
 * @site http://github.com/jimmiw/php-time-ago
 */
class TimeAgo {
    
    // defines the number of seconds per "unit"
    private $secondsPerMinute = 60;
    private $secondsPerHour = 3600;
    private $secondsPerDay = 86400;
    private $secondsPerMonth = 2592000;
    private $secondsPerYear = 31104000;
    private $timezone;

    public function __construct($timezone = null) {
        // if the $timezone is null, we take 'Europe/London' as the default
        // this was done, because the parent construct tossed an exception
        if($timezone == null) {
            $timezone = 'Europe/London';
        }

        $this->timezone = $timezone;
    }

    public function inWords($past, $time_language, $now = "now") {
        // sets the default timezone
        date_default_timezone_set($this->timezone);
        // finds the past in datetime
        $past = strtotime($past);
        // finds the current datetime
        $now = strtotime($now);

        // finds the time difference
        $timeDifference = $now - $past;

        // less than 29secs
        if($timeDifference <= 29) {
            $key = 'less_than_a_minute';
        }
        // more than 29secs and less than 1min29secss
        else if($timeDifference > 29 && $timeDifference <= 89) {
            $key = '1_minute';
        }
        // between 1min30secs and 44mins29secs
        else if($timeDifference > 89 &&
            $timeDifference <= (($this->secondsPerMinute * 44) + 29)
        ) {
            $replace = floor($timeDifference / $this->secondsPerMinute);
            $key = '_minutes';
        }
        // between 44mins30secs and 1hour29mins29secs
        else if(
            $timeDifference > (($this->secondsPerMinute * 44) + 29)
            &&
            $timeDifference < (($this->secondsPerMinute * 89) + 29)
        ) {
            $key = 'about_1_hour';
        }
        // between 1hour29mins30secs and 23hours59mins29secs
        else if(
            $timeDifference > (
                ($this->secondsPerMinute * 89) +
                29
            )
            &&
            $timeDifference <= (
                ($this->secondsPerHour * 23) +
                ($this->secondsPerMinute * 59) +
                29
            )
        ) {
            $replace = floor($timeDifference / $this->secondsPerHour);
            if($replace == 1){
                $key = 'about_1_hour';
                unset($replace);
            } else
                $key = '_hours';
        }
        // between 23hours59mins30secs and 47hours59mins29secs
        else if(
            $timeDifference > (
                ($this->secondsPerHour * 23) +
                ($this->secondsPerMinute * 59) +
                29
            )
            &&
            $timeDifference <= (
                ($this->secondsPerHour * 47) +
                ($this->secondsPerMinute * 59) +
                29
            )
        ) {
            $key = '1_day';
        }
        // between 47hours59mins30secs and 29days23hours59mins29secs
        else if(
            $timeDifference > (
                ($this->secondsPerHour * 47) +
                ($this->secondsPerMinute * 59) +
                29
            )
            &&
            $timeDifference <= (
                ($this->secondsPerDay * 29) +
                ($this->secondsPerHour * 23) +
                ($this->secondsPerMinute * 59) +
                29
            )
        ) {
            $replace = floor($timeDifference / $this->secondsPerDay);
            $key = '_days';
        }
        // between 29days23hours59mins30secs and 59days23hours59mins29secs
        else if(
            $timeDifference > (
                ($this->secondsPerDay * 29) +
                ($this->secondsPerHour * 23) +
                ($this->secondsPerMinute * 59) +
                29
            )
            &&
            $timeDifference <= (
                ($this->secondsPerDay * 59) +
                ($this->secondsPerHour * 23) +
                ($this->secondsPerMinute * 59) +
                29
            )
        ) {
            $key = 'about_1_month';
        }
        // between 59days23hours59mins30secs and 1year (minus 1sec)
        else if(
            $timeDifference > (
                ($this->secondsPerDay * 59) +
                ($this->secondsPerHour * 23) +
                ($this->secondsPerMinute * 59) +
                29
            )
            &&
            $timeDifference < $this->secondsPerYear
        ) {
            $replace = round($timeDifference / $this->secondsPerMonth);
            // if months is 1, then set it to 2, because we are "past" 1 month
            if($replace == 1) {
                $replace = 2;
            }

            $key = '_months';
        }
        // between 1year and 2years (minus 1sec)
        else if(
            $timeDifference >= $this->secondsPerYear
            &&
            $timeDifference < ($this->secondsPerYear * 2)
        ) {
            $key = 'about_1_year';
        }
        // 2years or more
        else {
            $replace = floor($timeDifference / $this->secondsPerYear);
            $key = 'over_x_years';
        }

        if(!isset($key))
            return '';

        if(is_array($time_language[$key])){
            if(function_exists('pluralForm')){
                if(isset($replace)){
                    return str_replace('{x}', $replace, pluralForm($replace, $time_language[$key]));
                } else {
                    return 'Plural specified but replace not set for ' . Output::getClean($key);
                }
            } else {
                return 'Plural form function not defined';
            }
        } else {
            if(isset($replace)){
                return str_replace('{x}', $replace, $time_language[$key]);
            } else {
                return $time_language[$key];
            }
        }
    }

    public function dateDifference($past, $now = "now") {
        // initializes the placeholders for the different "times"
        $seconds = 0;
        $minutes = 0;
        $hours = 0;
        $days = 0;
        $months = 0;
        $years = 0;

        // sets the default timezone
        date_default_timezone_set($this->timezone);

        // finds the past in datetime
        $past = strtotime($past);
        // finds the current datetime
        $now = strtotime($now);

        // calculates the difference
        $timeDifference = $now - $past;

        // starts determining the time difference
        if($timeDifference >= 0) {
            switch($timeDifference) {
                // finds the number of years
                case ($timeDifference >= $this->secondsPerYear):
                    // uses floor to remove decimals
                    $years = floor($timeDifference / $this->secondsPerYear);
                    // saves the amount of seconds left
                    $timeDifference = $timeDifference-($years * $this->secondsPerYear);

                // finds the number of months
                case ($timeDifference >= $this->secondsPerMonth && $timeDifference <= ($this->secondsPerYear-1)):
                    // uses floor to remove decimals
                    $months = floor($timeDifference / $this->secondsPerMonth);
                    // saves the amount of seconds left
                    $timeDifference = $timeDifference-($months * $this->secondsPerMonth);

                // finds the number of days
                case ($timeDifference >= $this->secondsPerDay && $timeDifference <= ($this->secondsPerYear-1)):
                    // uses floor to remove decimals
                    $days = floor($timeDifference / $this->secondsPerDay);
                    // saves the amount of seconds left
                    $timeDifference = $timeDifference-($days * $this->secondsPerDay);

                // finds the number of hours
                case ($timeDifference >= $this->secondsPerHour && $timeDifference <= ($this->secondsPerDay-1)):
                    // uses floor to remove decimals
                    $hours = floor($timeDifference / $this->secondsPerHour);
                    // saves the amount of seconds left
                    $timeDifference = $timeDifference-($hours * $this->secondsPerHour);

                // finds the number of minutes
                case ($timeDifference >= $this->secondsPerMinute && $timeDifference <= ($this->secondsPerHour-1)):
                    // uses floor to remove decimals
                    $minutes = floor($timeDifference / $this->secondsPerMinute);
                    // saves the amount of seconds left
                    $timeDifference = $timeDifference-($minutes * $this->secondsPerMinute);

                // finds the number of seconds
                case ($timeDifference <= ($this->secondsPerMinute-1)):
                    // seconds is just what there is in the timeDifference variable
                    $seconds = $timeDifference;
            }
        }

        $difference = array(
            "years" => $years,
            "months" => $months,
            "days" => $days,
            "hours" => $hours,
            "minutes" => $minutes,
            "seconds" => $seconds
        );

        return $difference;
    }
}
