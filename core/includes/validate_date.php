<?php
/*
 *	Made by Samerton
 *  http://worldscapemc.co.uk
 *
 *  License: MIT
 */

// Validate date function
function validateDate($date, $format = 'm/d/Y'){
    $d = DateTime::createFromFormat($format, $date);
    return $d && $d->format($format) == $date;
}