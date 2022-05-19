<?php

function isValidStringForEmail(mixed $value): bool
{
    if (is_string($value)){
        return true;
    }
    return false;
}

function filterInputForEmail(string $inputName, int $inputType = INPUT_GET): ?string
{
    return filter_input($inputType, $inputName, FILTER_SANITIZE_EMAIL);
}

function filterVarForEmail(string $value): mixed
{
    return filter_var($value, FILTER_SANITIZE_EMAIL);
}

function validateEmail(string $email): mixed
{
    return filter_var($email, FILTER_VALIDATE_EMAIL);
}

// Validates result string before its evaluation by inclusion
function validateEmailExpression(string $string): mixed
{
    // Remove quotes
    $string = preg_replace('/"/', '', $string);
    $string = preg_replace("/'/", '', $string);
    echo "<p>\$string = $string</p>";

    // Remove all operators
    $string = preg_replace('/\*|\?|:|\(|\)|=|!|<|>/', '', $string);
    echo "<p>\$string = $string</p>";

    // Remove all spaces
    $string = preg_replace('/\s+/', '', $string);
    echo "<p>\$string = $string</p>";

    // Remove multiple dots
    $string = preg_replace('/\.+/', '.', $string);
    echo "<p>\$string = $string</p>";

    // Remove dots around @
    $string = preg_replace('/\.@\./', '@', $string);
    echo "<p>\$string = $string</p>";

    // Validate remaining characters
    return validateEmail($string);
}

function addQuotes(string $string): string
{
    return "'" . $string . "'";
}

function spaceLess(string $string): string
{
    return str_replace(' ', '', $string);
}

function ifDebug(string $expr): ?string
{
    global $debug;
    if ($debug) {
        return $expr;
    } else {
        return null;
    }
}