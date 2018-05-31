<?php

/**
 * Natural sort by key
 *
 * @author http://php.net/manual/es/function.natsort.php#87706
 * @param array $array
 */
function knatsort(array &$array)
{
    uksort($array, "strnatcmp");
}
