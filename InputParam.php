<?php
require './Patterns.php';

/*
 * @todo
 * write string in processed value state
 */

class InputParam
{
    protected bool $isValid = false;
    protected bool $returnMethodResult = false;
    protected string $originalValue;
    protected string $processedValue;

    public function __construct(protected string $name, string $value, protected bool $debug = false)
    {
        $this->originalValue = $value;
        $this->processedValue = $value;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }

    /**
     * @param string $name
     */
    public function setName(name $name)
    {
        $this->name = $name;
    }

    /**
     * @return string
     */
    public function getOriginalValue(): string
    {
        return $this->originalValue;
    }

    /**
     * @return string
     */
    public function getOriginalValueQuoted(): string
    {
        return addQuotes(spaceLess(strtolower($this->originalValue)));
    }

    /**
     * @param string $value
     */
    public function setOriginalValue(string $value)
    {
        $this->originalValue = $value;
    }

    /**
     * @return string
     */
    public function getProcessedValue(): string
    {
        return $this->processedValue;
    }

    /**
     * @param string $value
     */
    public function setProcessedValue(string $value)
    {
        $this->processedValue = $value;
    }

    protected function resetProcessedValue()
    {
        $this->processedValue = $this->getOriginalValue();
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->isValid;
    }

    /**
     * @param bool $isValid
     */
    public function setIsValid(bool $isValid)
    {
        $this->isValid = $isValid;
    }

    /**
     * @return bool
     */
    public function isReturnMethodResult(): bool
    {
        return $this->returnMethodResult;
    }

    /**
     * @param bool $returnMethodResult
     */
    public function setReturnMethodResult(bool $value): void
    {
        $this->returnMethodResult = $value;
    }

    /**
     * Returns words array from $this->getProcessedValue()
     * @return array
     */
    protected function getWords(): array
    {
        $this->setReturnMethodResult(false);
        return str_word_count(str_replace('-', ' ', $this->getProcessedValue()), 1);
    }

    /**
     * Returns words count from $this->getProcessedValue()
     * @return int
     */
    public function wordsCount(): int
    {
        $this->setReturnMethodResult(true);
        return str_word_count(str_replace('-', ' ', $this->getProcessedValue()));
    }

    /**
     * Returns a string concatenating
     * each word x first characters from $this->processedValue.
     * @param int $n
     * @return $this
     */
    public function eachWordFirstChars(int $n): InputParam
    {
        $this->setReturnMethodResult(false);
        $words = $this->getWords();
        $firstChars = array_map(fn($value): string => substr($value, 0, $n), $words);
        $this->setProcessedValue(implode('', $firstChars));
        return $this;
    }

    /**
     * Returns a string concatenating
     * each word x last characters from $this->processedValue.
     * @param int $n
     * @return $this
     */
    public function eachWordLastChars(int $n): InputParam
    {
        $this->setReturnMethodResult(false);
        $words = $this->getWords();
        $lastChars = array_map(fn($value): string => substr($value, -$n), $words);
        $this->setProcessedValue(implode('', $lastChars));
        return $this;
    }

    /**
     * Keeps "first" x words from $this->processedValue
     * @param int $n
     * @return $this
     */
    public function firstWords(int $n): InputParam
    {
        $this->setReturnMethodResult(false);
        $words = $this->getWords();
        if ($n > 0) {
            $firstWords = array_slice($words, 0, $n); // Keeps $n words from the left
        } elseif ($n < 0) {
            $firstWords = array_slice($words, $n); // Keeps $n words from the right
        }
        $this->setProcessedValue(implode(' ', $firstWords));
        return $this;
    }

    /**
     * Keeps "last" x words from $this->processedValue
     * @param int $n
     * @param bool $intuitive
     * @return $this
     */
    public function lastWords(int $n, bool $intuitive = false): InputParam
    {
        $this->setReturnMethodResult(false);
        $words = $this->getWords();

        // Intuitive behavior mode (1 to $n indexation mode)
        if ($intuitive) {
            if ($n > 0) {
                $lastWords = array_slice($words, count($words) - $n); // Keeps $n words from the right
            } elseif ($n < 0) {
                $lastWords = array_slice($words, 0, $n); // Keeps $n words from the left
            }

        } else { // Behaves strictly like in given example (0 to $n indexation mode)
            if ($n > 0) {
                $lastWords = array_slice($words, count($words) - ($n + 1)); // Skip $n + 1 words from the left
            } elseif ($n < 0) {
                $lastWords = array_slice($words, 0, count($words) - (abs($n)+1)); // Skip $n + 1 words from the right
            }
        }
        $this->setProcessedValue(implode(' ', $lastWords));
        return $this;
    }

    /**
     * @php builtin function proxy
     * Keeps the portion of string specified by the offset and length parameters.
     * @param int $offset
     * @param ?int $length
     * @return $this
     */
    public function substr(int $offset, ?int $length = null)
    {
        $this->setProcessedValue(substr($this->getProcessedValue(), $offset, $length));
        return $this;
    }

    /**
     * Executes requested string treatment method(s) for this InputParam instance
     * @param array $matches
     * @return mixed
     */
    public function dispatch(array $matches)
    {
        global $offset;
        $this->resetProcessedValue();

        if ($this->debug) {
            echo "<p>";
            echo "Dump de \$matches dans InputParam::dispatch()<br />";
            var_dump($matches);
            echo "<br /><br />Dump de \$this->getProcessedValue() BEFORE dans InputParam::dispatch()<br />";
            echo "<br />";
            var_dump($this->getProcessedValue());
            echo "<br />";
        }

        $chain = $matches[0][0];
        $inputIndex = $matches['input'][0];
        $method = $matches['method'][0];
        $parameter1 = $matches['parameter1'][0];

        // Single method call, execute it
        if (strlen($chain) <= strlen($method) + strlen($parameter1) + 9) {

            echo ifDebug('<p>SINGLE CALL<br />');
            echo ifDebug("\$method = $method<br />");
            echo ifDebug("\$parameter1 = $parameter1</p>");

            $result = $this->$method($parameter1);
        }
        // Chained methods calls, decompose for sequential execution
        else {

            echo ifDebug('<p>CHAINED CALLS</p>');

            $calls = ['chain' => $chain,
                    'inputIndex' => $inputIndex,
                    'last_method_matched' => $method,
                    'last_parameter_matched' => $parameter1];
            $result = $this->chainedCall($calls);
        }

        if ($this->debug) {
            echo "<br /><br />Dump de \$this->getProcessedValue() / \$result AFTER dans InputParam::dispatch()<br />";
            echo "<br />";
            var_dump($result);
            echo "<br />";
            var_dump($this->getProcessedValue());
            echo "</p>";
        }

        // For methods that directly return evaluated result in expression
        if ($this->isReturnMethodResult()) {
            // update global offset position : pass method call position + replacement expression length
            $offset = $matches[0][1] + strlen($result);
            return $result;

        // For methods that alter $this->ProcessedValue before it is returned
        } else {
            $processedValue = $this->getProcessedValue();
            // update global offset position : pass method call position + replacement expression length
            $offset = $matches[0][1] + strlen($processedValue);
            return addQuotes(spaceLess(strtolower($processedValue)));
        }
    }

    /**
     * Executes a chain of string treatment method calls on $this->getProcessedValue()
     * @param array $calls
     * @return mixed
     */
    protected function chainedCall(array $calls): mixed
    {
        $return = preg_match_all('#'. Patterns::METHOD_CALL_PATTERN .'#', $calls['chain'], $matches);

        if ($return > 0) {

            if ($this->debug) {
                echo "<hr /><p>";
                echo "Dump de \$matches dans InputParam::chainedCall()<br />";
                var_dump($matches);
                echo "</p>";
            }

            foreach ($matches['method'] as $index => $method) {

                $paramCount = 0;
                if (isset($matches['parameter1'])) {
                    $paramCount = 1;
                    $parameter1 = $matches['parameter1'][$index];
                }
                if (isset($matches['parameter2'])) {
                    $paramCount = 2;
                    $parameter2 = $matches['parameter2'][$index];
                }

                echo ifDebug("<p>\$index = $index<br />");
                echo ifDebug("\$method = $method<br />");
                echo ifDebug("\$parameter1 = $parameter1</p>");
                echo ifDebug("\$parameter2 = $parameter2</p>");

                switch ($paramCount) {
                    case 0:
                        $result = $this->$method();
                        break;
                    case 1:
                        $result = $this->$method($parameter1);
                        break;
                    case 2:
                        $result = $this->$method($parameter1, $parameter2);
                        break;
                }

                if ($this->debug) {
                    echo "<hr /><p>Dump de \$this->getProcessedValue() dans InputParam::chainedCall()<br />";
                    echo "<br />";
                    var_dump($this->getProcessedValue());
                    echo "</p>";
                    echo "<hr /><p>Dump de \$this->getWords() dans InputParam::chainedCall()<br />";
                    echo "<br />";
                    var_dump($this->getWords());
                    echo "</p>";
                }
            }
            return $result;
        }
    }

    /**
     * @return string
     */
    public function __toString():string
    {
        return $this->getProcessedValue();
    }

}