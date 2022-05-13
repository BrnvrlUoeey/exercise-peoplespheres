<?php
require './utils.php';
require './InputParam.php';

ini_set('memory_limit', '1024M');
const EXPR_EVAL_MAX_ALLOWED_ITERATIONS = 15;

$output = '';
$expression = '';
$input = $_GET;
$input = [
    'Input1' => 'Jean-Louis',
    'Input2' => 'Jean-Charles Mignard',
    'Input3' => 'external',
    'Input4' => 'peoplespheres.fr',
    'Input5' => 'fr',
    'expression' => "input1.eachWordFirstChars(1) ~ '.' ~ (input2.wordsCount() > 1 ?
    input2.lastWords(-1).eachWordFirstChars(1) ~ input2.lastWords(1) :
    input2 ) ~ '@' ~ input3 ~ '.' ~ input4 ~ '.' ~ input5"
];

$count = 1;
$params = [];

// Parameters check and filtering
foreach ($input as $name => $val) {

    echo "<p>\$name = <b>$name</b><br />\$val = $val</p>";

    if ('expression' === $name && is_string($val)) {
        $expression = $val;

        echo "\$expression = $expression<br />";

    } elseif (filterVarForEmail($val)) {

        // Preserve spaces in value when sanitizing
        $val = str_replace(' ', '[#[SPACE]#]', $val);
        // Sanitize
        $filteredVal = filterVarForEmail($val);
        // Restore spaces after sanitizing
        $filteredVal = str_replace('[#[SPACE]#]', ' ', $filteredVal);

        echo "\$filteredVal = $filteredVal<br />";

        $param = new InputParam($name, $filteredVal);
        $params[ucfirst($name)] = $param;
    }
    $count++;
    echo "<p>\$count = $count</p>";
    echo '<hr />';
}

// Order parameters
ksort($params);

var_dump($params);
/*
 * Input
Any number of in-query parameters named “input1”,”input2”, ”input3”..
Example :
Input1 : “Jean-Louis”
Input2 : “Jean-Charles Mignard”
Input3 : “external”
Input4 : “peoplespheres.fr”
Input5 : “fr”
*/
/* A query parameter named “expression”.
Example * /
input1.eachWordFirstChars(1) ~ '.' ~
    (
        input2.wordsCount() > 1 ?
            input2.lastWords(-1).eachWordFirstChars(1) ~ input2.lastWords(1) :
            input2
    ) ~ '@' ~ input3 ~ '.' ~ input4 ~ '.' ~ input5

/* Résultat :
{
    "data": [
        {
            "id": "jl.jccharlesmignard@external.peoplespheres.fr",
            "value": "jl.jccharlesmignard@external.peoplespheres.fr"
        }
    ]
}*/

// Expression evaluation
$substitutes = array();
$buffer = $expression;
$i = 0;
$offset = 0;
$matches = [];

if (!empty($buffer)){

    echo('<hr/>');
    var_dump('<br/>$buffer :<br/>', $buffer, '<br/>$matches :<br/>', $matches, '<br/>$offset : ', $offset, '<br/>$i : ', $i, );
    echo('<hr/>');

    while (preg_match('#'. Patterns::FULL_METHOD_CALL_PATTERN .'#', $buffer, $matches, 0, $offset) && $i <= EXPR_EVAL_MAX_ALLOWED_ITERATIONS) {

        echo "<hr /><p><b style=\"color:blue\">\$i = $i</b><br />";
        echo "\$offset = $offset<br />";
        echo "\$buffer = <span style='color:red'>$buffer</span><br />";

        echo "Dump de \$matches :<br />";
        var_dump($matches);
        echo "<br />";

        $inputIndex = $matches['input'];
        $inputToProcess = $params["Input$inputIndex"];

        echo "Dump de \$inputToProcess :<br />";
        var_dump($inputToProcess);
        echo "</p>";

        $i++;
        $matches = [];

        // Add callback with targeted $input
        $substitutes['#'. Patterns::FULL_METHOD_CALL_PATTERN .'#'] = array($inputToProcess, 'dispatch');

        $buffer = preg_replace_callback_array(
            $substitutes, // Patterns & callbacks
            $buffer, // Subject
            1,  // Limit number of matches
            $countReplacements, // Count replacements
            PREG_OFFSET_CAPTURE
        );
    }
}


// logical expression handling


// Data object encapsulation


// Data object output
