<?php

/**
 * Check is integer value
 *
 * @param $value
 * @return bool
 */
function check_is_integer($value): bool
{
    return $value == (int)$value;
}
