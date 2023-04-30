<?php
/**
 * Basic date helper functions
 *
 * @package NamelessMC\Core
 * @author Samerton
 * @version 2.1.0
 * @license MIT
 */
class Date {

    /**
     * Gets next date from now (or a specified date)
     *
     * @param ?string $from Optional date string to add a day onto
     * @return DateTime Instance of next date
     */
    public static function next(string $from = null): DateTime {
        $interval = new DateInterval('P1D');

        if ($from) {
            $from = date('d-M-Y', strtotime($from));
            $date = DateTime::createFromFormat('d-M-Y', $from);
        } else {
            $date = new DateTime();
        }

        return $date->setTime(0, 0)->add($interval);
    }
}
