<?php
declare(strict_types=1);

/**
 * ITCS333 A4 starter – implement the three pure validation functions.
 * They are unit-tested by the autograder (tests/UnitTest.php).
 */

function validate_required(string $v): bool
{
    return trim($v) !== '';
}

function validate_email(string $v): bool
{
    $trimmed = trim($v);
    if ($trimmed === '') {
        return false;
    }

    return filter_var($trimmed, FILTER_VALIDATE_EMAIL) !== false;
}

function validate_length(string $v, int $min, int $max): bool
{
    $trimmed = trim($v);
    $len = function_exists('mb_strlen')
        ? mb_strlen($trimmed, 'UTF-8')
        : strlen($trimmed);

    return $len >= $min && $len <= $max;
}