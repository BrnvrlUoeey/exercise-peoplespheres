<?php
class Patterns
{
    // Input tokens
    const INPUT_PATTERN = 'input(?<input>\d)';
    // Subject to method combinator
    const JUNCTION_PATTERN = '\.';
    // Supported methods
    const METHOD_NAME_PATTERN = '(?<method>wordsCount|eachWordFirstChars|eachWordLastChars|firstWords|lastWords|substr)';
    // Supported parameters  (0, 1 as int, 2 as int|bool)
    const PARAMETER_PATTERN = '\((?<parameter1>-?\d*)(?:,\s*(?<parameter2>true|false|\d+))?\)';
    // Single method call
    const METHOD_CALL_PATTERN = self::JUNCTION_PATTERN . self::METHOD_NAME_PATTERN . self::PARAMETER_PATTERN;
    // Optionnally chained method call
    const FULL_METHOD_CALL_PATTERN = self::INPUT_PATTERN . '(' . self::METHOD_CALL_PATTERN . ')+';
}