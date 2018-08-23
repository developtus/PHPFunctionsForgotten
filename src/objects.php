<?php

/**
 * Convert an object to array
 *
 * @param $object
 * @return array
 */
function object_to_array(object $object): array
{
    return json_decode(json_encode($object), true);
}
