<?php
require './utils.php';
require './InputParam.php';
require './EmailData.php';

ini_set('memory_limit', '1024M');
const EXPR_EVAL_MAX_ALLOWED_ITERATIONS = 50;

$output = '';
$expression = '';
$concatOperator = '~';
$evalFilePath = './output';
$input = $_GET;
$debug = isset($_GET['debug']);
//
//echo "<pre>";
//var_dump($_GET, $debug);
//echo "</pre>";


//$input = [
//    'Input1' => 'Jean-Louis',
//    'Input2' => 'Jean-Charles Mignard',
//    'Input3' => 'external',
//    'Input4' => 'peoplespheres.fr',
//    'Input5' => 'fr',
//    'expression' => "input1.eachWordFirstChars(1) ~ '.' ~ (input2.wordsCount() > 1 ?
//    input2.lastWords(-1).eachWordFirstChars(1) ~ input2.lastWords(1) :
//    input2 ) ~ '@' ~ input3 ~ '.' ~ input4 ~ '.' ~ input5"
//];

$count = 1;
$params = [];

//////////////////////////////////////////////////////////////////////////////
// INPUT PARAMETER CHECK AND FILTERING
//////////////////////////////////////////////////////////////////////////////

// Foreach Input parameter
foreach ($input as $name => $val) {

    echo ifDebug("<p>\$name = <b>$name</b><br />\$val = $val</p>");

    if ('expression' === $name && is_string($val)) {
        $expression = $val;

        echo ifDebug("\$expression = $expression<br />");

    } elseif (filterVarForEmail($val)) {

        // Preserve spaces in value when sanitizing
        $val = str_replace(' ', '[#[SPACE]#]', $val);
        // Sanitize
        $filteredVal = filterVarForEmail($val);
        // Restore spaces after sanitizing
        $filteredVal = str_replace('[#[SPACE]#]', ' ', $filteredVal);

        echo ifDebug("\$filteredVal = $filteredVal<br />");

        $param = new InputParam($name, $filteredVal, $debug);
        $params[ucfirst($name)] = $param;
    }
    $count++;
    echo ifDebug("<p>\$count = $count</p><hr />");
}

// Order parameters
ksort($params);

if ($debug) {
    echo "<pre>";
    var_dump($params);
    echo "</pre>";
}

//////////////////////////////////////////////////////////////////////////////
// EXPRESSION EVALUATION
//////////////////////////////////////////////////////////////////////////////

/*****************************************************************************
    Methods calls sequential evaluation
******************************************************************************/
$substitutes = array();
$buffer = $expression;
$i = 0;
$offset = 0;
$matches = [];

if (!empty($buffer)){

    if ($debug) {
        echo('<hr/>');
        var_dump('<br/>$buffer :<br/>', $buffer, '<br/>$matches :<br/>', $matches, '<br/>$offset : ', $offset, '<br/>$i : ', $i, );
    }

    // While there is still some method call to catch and evaluate
    while (preg_match('#'. Patterns::FULL_METHOD_CALL_PATTERN .'#', $buffer, $matches, 0, $offset) && $i <= EXPR_EVAL_MAX_ALLOWED_ITERATIONS) {

        $inputIndex = $matches['input'];
        $inputToProcess = $params["Input$inputIndex"];

        if ($debug) {
            echo "<hr /><p><b style=\"color:blue\">\$i = $i</b><br />";
            echo "\$offset = $offset<br />";

            echo "Dump de \$matches :<br /><pre>";
            var_dump($matches);
            echo "</pre><br />";

            echo "Dump de \$inputToProcess :<br />";
            var_dump($inputToProcess);
            echo "</p>";
        }

        $i++;
        $matches = [];

        // Takes callback with targeted $input
        $substitutes['#'. Patterns::FULL_METHOD_CALL_PATTERN .'#'] = array($inputToProcess, 'dispatch');

        // Evaluate next method(s) call(s) chain via Callback on specific InputParam object
        $buffer = preg_replace_callback_array(
            $substitutes, // Patterns & callbacks
            $buffer, // Subject
            1,  // Limit number of matches
            $countReplacements, // Count replacements
            PREG_OFFSET_CAPTURE
        );

        echo ifDebug("\$buffer = <span style='color:green'>$buffer</span><br />");
    }
}

/*****************************************************************************
  Litteral Inputs reference substitutions
******************************************************************************/
$i = 0;
$offset = 0;

// While there is still Litteral Inputs reference to replace
while (preg_match('#'. Patterns::INPUT_PATTERN .'#', $buffer, $matches, 0, $offset) && $i <= EXPR_EVAL_MAX_ALLOWED_ITERATIONS) {

    $inputIndex = $matches['input'];
    $inputToProcess = $params["Input$inputIndex"];

    if ($debug) {
        echo "Dump de \$inputToProcess :<br />";
        var_dump($inputToProcess);
        echo "</p>";
    }

    // Takes callback with targeted $input
    $substitutes['#'. Patterns::INPUT_PATTERN .'#'] = array($inputToProcess, 'getOriginalValueQuoted');

    $buffer = preg_replace_callback_array(
        $substitutes, // Patterns & callbacks
        $buffer, // Subject
        1,  // Limit number of matches
        $countReplacements, // Count replacements
        PREG_OFFSET_CAPTURE
    );

    echo ifDebug("\$buffer = <span style='color:violet'>$buffer</span><br />");
}

/*****************************************************************************
  Concaternation operator substitution
******************************************************************************/
$buffer = str_replace($concatOperator, '.', $buffer);

echo ifDebug("\$buffer = <span style='color:red'>$buffer</span><br />");


/*****************************************************************************
  Resulting expression writing in PHP file
*******************************************************************************/
$evalResult = false;
$outputFilePath = "$evalFilePath/interpolation_result.php";
$fp = fopen($outputFilePath, "w");

if (is_file($outputFilePath) && is_writable($outputFilePath)) {
    $written = fwrite($fp, "<?php return ($buffer);");

    // logical expression evaluation
    if ($written) {
        $evalResult = include($outputFilePath);
    }
}

echo ifDebug("<h2>\$evalResult :</h2> <p><span style='color:red'><b>$evalResult</b></span></p>");


/*****************************************************************************
  Result expression file inclusion for evaluation in variable
*******************************************************************************/

// Data object encapsulation

if (!empty($evalResult)) {
    $attributes = new StdClass();
    $attributes->value = $evalResult;
    $dataObject = new EmailData($evalResult, $attributes);
}

// Data object output
header("HTTP/2 200 OK");
header('Content-Type: application/vnd.api+json');
echo json_encode($dataObject);