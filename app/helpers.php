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

/**
 * Checking string is email
 *
 * @param string|null $email
 * @return bool
 */
function is_email(string $email = null): bool
{
    return $email && filter_var($email, FILTER_VALIDATE_EMAIL);
}
