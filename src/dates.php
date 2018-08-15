<?php

/**
 * Validate a date by date format
 *
 * @param string $date date to validate
 * @param string $format date format to validate
 * @return bool
 */
function validate_date(string $date, string $format = 'Y-m-d H:i:s'): bool
{
    $d = \DateTime::createFromFormat($format, $date);

    return $d instanceof \DateTime && $d->format($format) === $date;
}
