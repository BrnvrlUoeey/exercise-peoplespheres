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

function addQuotes(string $string): string
{
    return "'" . $string . "'";
}

function spaceLess(string $string): string
{
    return str_replace(' ', '', $string);
}