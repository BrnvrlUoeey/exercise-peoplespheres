<?php
class Patterns
{
    const INPUT_PATTERN = 'input(?<input>\d)';
    const JUNCTION_PATTERN = '\.';
    const METHOD_NAME_PATTERN = '(?<method>wordsCount|eachWordFirstChars|eachWordLastChars|firstWords|lastWords)';
    const PARAMETER_PATTERN = '\((?<parameter>-?\d*)\)';
    const METHOD_CALL_PATTERN = self::JUNCTION_PATTERN . self::METHOD_NAME_PATTERN . self::PARAMETER_PATTERN;
    const FULL_METHOD_CALL_PATTERN = self::INPUT_PATTERN . '(' . self::METHOD_CALL_PATTERN . ')+';
}